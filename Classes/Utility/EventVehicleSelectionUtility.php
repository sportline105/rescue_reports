<?php
declare(strict_types=1);
namespace nkfire\RescueReports\Utility;

use Doctrine\DBAL\ArrayParameterType;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EventVehicleSelectionUtility
{
    public function getAvailableVehicles(array &$config): void
    {
        $eventRow = $config['row'] ?? [];

        if (empty($eventRow['stations'])) {
            return;
        }

        $stationField = $eventRow['stations'];
        $stationIds = is_array($stationField)
            ? array_map('intval', $stationField)
            : GeneralUtility::intExplode(',', (string)$stationField, true);

        if ($stationIds === []) {
            return;
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_vehicle');

        $queryBuilder = $connection->createQueryBuilder();

        $vehicles = $queryBuilder
            ->select(
                'v.uid',
                'v.name',
                's.name AS station_name',
                's.sorting AS station_sorting',
                's.is_primary AS station_is_primary',
                'b.uid AS brigade_uid',
                'b.name AS brigade_name',
                'b.sorting AS brigade_sorting'
            )
            ->from('tx_rescuereports_domain_model_vehicle', 'v')
            ->innerJoin('v', 'tx_rescuereports_domain_model_station', 's', 'v.station = s.uid')
            ->leftJoin('s', 'tx_rescuereports_domain_model_brigade', 'b', 's.brigade = b.uid')
            ->where(
                $queryBuilder->expr()->in(
                    'v.station',
                    $queryBuilder->createNamedParameter($stationIds, ArrayParameterType::INTEGER)
                )
            )
            ->orderBy('b.sorting')
            ->addOrderBy('station_sorting')
            ->addOrderBy('v.name')
            ->executeQuery()
            ->fetchAllAssociative();

        // Detect which brigades contain a primary station
        $primaryBrigadeUids = [];
        foreach ($vehicles as $vehicle) {
            if ((bool)$vehicle['station_is_primary']) {
                $primaryBrigadeUids[] = (int)$vehicle['brigade_uid'];
            }
        }

        $grouped = [];

        foreach ($vehicles as $vehicle) {
            $brigadeUid = (int)$vehicle['brigade_uid'];
            $sortPrefix = in_array($brigadeUid, $primaryBrigadeUids, true)
                ? '-1'
                : str_pad((string)(int)($vehicle['brigade_sorting'] ?? 999999), 10, '0', STR_PAD_LEFT);

            $groupLabel = $sortPrefix . '_' . ($vehicle['brigade_name'] ?? 'Unbekannt');
            $itemLabel = $vehicle['station_name'] . ' – ' . $vehicle['name'];
            $grouped[$groupLabel][] = [$itemLabel, (int)$vehicle['uid']];
        }

        ksort($grouped);

        foreach ($grouped as $groupLabel => $items) {
            $config['items'][] = [explode('_', $groupLabel, 2)[1], '--div--'];
            foreach ($items as $item) {
                $config['items'][] = $item;
            }
        }

        $alreadySelectedIds = array_values(array_filter(
            array_unique(array_map('intval', array_column($config['itemArray'] ?? [], 1)))
        ));

        if ($alreadySelectedIds !== []) {
            $existingQueryBuilder = $connection->createQueryBuilder();

            $existing = $existingQueryBuilder
                ->select('v.uid', 'v.name', 's.name AS station_name', 'b.name AS brigade_name')
                ->from('tx_rescuereports_domain_model_vehicle', 'v')
                ->innerJoin('v', 'tx_rescuereports_domain_model_station', 's', 'v.station = s.uid')
                ->leftJoin('s', 'tx_rescuereports_domain_model_brigade', 'b', 's.brigade = b.uid')
                ->where(
                    $existingQueryBuilder->expr()->in(
                        'v.uid',
                        $existingQueryBuilder->createNamedParameter($alreadySelectedIds, ArrayParameterType::INTEGER)
                    )
                )
                ->executeQuery()
                ->fetchAllAssociative();

            foreach ($existing as $vehicle) {
                $label = $vehicle['station_name'] . ' – ' . $vehicle['name'];
                $config['items'][] = [$label, (int)$vehicle['uid']];
            }
        }
    }
}