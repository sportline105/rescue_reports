<?php

namespace In2code\Firefighter\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

namespace In2code\Firefighter\Utility;

class EventVehicleAssignmentUtility {
public function debugAssignmentOptions(array &$config): void
{
    $config['items'][] = ['Test-Fahrzeug A', 1];
    $config['items'][] = ['Test-Fahrzeug B', 2];
}


    protected function getRelatedStationUids(int $eventUid): array
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_event_station_mm');

        $queryBuilder = $connection->createQueryBuilder();
        $rows = $queryBuilder
            ->select('uid_foreign')
            ->from('tx_firefighter_event_station_mm')
            ->where(
                $queryBuilder->expr()->eq('uid_local', $queryBuilder->createNamedParameter($eventUid))
            )
            ->executeQuery()
            ->fetchFirstColumn();

        return array_map('intval', $rows);
    }

    protected function getVehiclesByStations(array $stationUids): array
    {
        if (empty($stationUids)) {
            return [];
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_station_car_mm');

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder
            ->select(
                'sc.uid_foreign AS car_uid',
                's.name AS station_name',
                'c.name AS car_name'
            )
            ->from('tx_firefighter_station_car_mm', 'sc')
            ->innerJoin('sc', 'tx_firefighter_domain_model_station', 's', 's.uid = sc.uid_local')
            ->innerJoin('sc', 'tx_firefighter_domain_model_car', 'c', 'c.uid = sc.uid_foreign')
            ->where(
                $queryBuilder->expr()->in('sc.uid_local', $queryBuilder->createNamedParameter($stationUids, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY))
            );

        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }
}