<?php

namespace In2code\Firefighter\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class VehicleAssignmentUtility
{
    public function filterCarsByStation(array &$config)
    {
        $parentUid = (int)$config['row']['station'] ?? 0;

        if ($parentUid <= 0) {
            return;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_station_car_mm')
            ->createQueryBuilder();

        $queryBuilder
            ->select('uid_foreign')
            ->from('tx_firefighter_station_car_mm')
            ->where(
                $queryBuilder->expr()->eq('uid_local', $queryBuilder->createNamedParameter($parentUid, \PDO::PARAM_INT))
            );

        $carUids = array_column($queryBuilder->executeQuery()->fetchAllAssociative(), 'uid_foreign');

        if (empty($carUids)) {
            return;
        }

        $carQueryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_domain_model_car')
            ->createQueryBuilder();

        $cars = $carQueryBuilder
            ->select('uid', 'name')
            ->from('tx_firefighter_domain_model_car')
            ->where(
                $carQueryBuilder->expr()->in('uid', $carQueryBuilder->createNamedParameter($carUids, ConnectionPool::PARAM_INT_ARRAY))
            )
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($cars as $car) {
            $config['items'][] = [$car['name'], $car['uid']];
        }
    }
}