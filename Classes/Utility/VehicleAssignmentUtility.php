<?php
namespace In2code\Firefighter\Utility;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class VehicleAssignmentUtility {
    public function filterCarsByStation(array &$config): void {
        // Station aus dem aktuellen Datensatz holen
        $stationUid = (int)($config['row']['station'][0] ?? 0);
        if ($stationUid === 0) {
            return; // Keine Station ausgewählt
        }

        // Fahrzeuge der Station aus der Datenbank holen
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_firefighter_domain_model_car');
        $cars = $queryBuilder
            ->select('uid', 'name')
            ->from('tx_firefighter_domain_model_car')
            ->where(
                $queryBuilder->expr()->in(
                    'uid',
                    $queryBuilder->createNamedParameter(
                        $this->getCarUidsByStation($stationUid),
                        \Doctrine\DBAL\Connection::PARAM_INT_ARRAY
                    )
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();

        // Items für das Select-Feld erstellen
        $config['items'] = [];
        foreach ($cars as $car) {
            $config['items'][] = [
                'label' => $car['name'],
                'value' => $car['uid'],
            ];
        }
    }

    protected function getCarUidsByStation(int $stationUid): array {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_firefighter_station_car_mm');
        $result = $queryBuilder
            ->select('uid_foreign')
            ->from('tx_firefighter_station_car_mm')
            ->where(
                $queryBuilder->expr()->eq('uid_local', $queryBuilder->createNamedParameter($stationUid, \PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchAllAssociative();

        return array_column($result, 'uid_foreign');
    }
}
