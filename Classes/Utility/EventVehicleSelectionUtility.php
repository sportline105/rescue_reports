<?php
namespace In2code\RescueReports\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class EventVehicleSelectionUtility
{
    public function getAvailableVehicles(array &$config): void
    {
        $eventRow = $config['row'] ?? [];

        if (empty($eventRow['stations'])) {
            return;
        }

        // Mehrfachauswahl absichern
        $stationField = $eventRow['stations'];
        $stationIds = is_array($stationField)
        ? array_map('intval', $stationField)
        : GeneralUtility::intExplode(',', $stationField, true);
        if (empty($stationIds)) {
            return;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_vehicle')
            ->createQueryBuilder();

        $vehicles = $queryBuilder
            ->select('v.uid', 'v.name', 's.name AS station_name')
            ->from('tx_rescuereports_domain_model_vehicle', 'v')
            ->innerJoin('v', 'tx_rescuereports_domain_model_station', 's', 'v.station = s.uid')
            ->where(
                $queryBuilder->expr()->in('v.station', $queryBuilder->createNamedParameter($stationIds, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY))
            )
            ->orderBy('s.name')
            ->addOrderBy('v.name')
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($vehicles as $vehicle) {
            $label = $vehicle['station_name'] . ' – ' . $vehicle['name'];
            $config['items'][] = [$label, (int)$vehicle['uid']];
        }
        // Stelle sicher, dass auch gespeicherte Fahrzeuge enthalten bleiben
        $alreadySelectedIds = array_unique(array_column($config['itemArray'] ?? [], 1));
        if (!empty($alreadySelectedIds)) {
            $existingVehicles = $queryBuilder
                ->select('v.uid', 'v.name', 's.name AS station_name')
                ->from('tx_rescuereports_domain_model_vehicle', 'v')
                ->innerJoin('v', 'tx_rescuereports_domain_model_station', 's', 'v.station = s.uid')
                ->where(
                    $queryBuilder->expr()->in('v.uid', $queryBuilder->createNamedParameter($alreadySelectedIds, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY))
                )
                ->executeQuery()
                ->fetchAllAssociative();
            foreach ($existingVehicles as $vehicle) {
                $label = $vehicle['station_name'] . ' – ' . $vehicle['name'];
                $config['items'][] = [$label, (int)$vehicle['uid']];
            }
        }
    }
}