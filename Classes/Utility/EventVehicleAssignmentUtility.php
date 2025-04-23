<?php

namespace In2code\Firefighter\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class EventVehicleAssignmentUtility
{
    /**
     * Diese Funktion wird per itemsProcFunc im Feld "event_vehicle_assignments" aufgerufen,
     * um nur Fahrzeuge pro Station anzuzeigen.
     *
     * @param array $config
     * @return void
     */
    public function getAssignmentOptions(array &$config): void
    {
        $config['items'] = [];

        // UID des aktuellen Event-Datensatzes
        $eventRow = $config['row'] ?? [];
        if (empty($eventRow['uid'])) {
            return;
        }

        $eventUid = (int)$eventRow['uid'];

        // Lade Stations für das Event aus der MM-Tabelle
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_event_station_mm');

        $queryBuilder = $connection->createQueryBuilder();
        $stationUids = $queryBuilder
            ->select('uid_foreign')
            ->from('tx_firefighter_event_station_mm')
            ->where(
                $queryBuilder->expr()->eq('uid_local', $queryBuilder->createNamedParameter($eventUid, \PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchFirstColumn();

        if (empty($stationUids)) {
            return;
        }

        // Lade Fahrzeuge aus den verknüpften Stationen über die MM-Tabelle station_car_mm
        $carConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_station_car_mm');

        $carQuery = $carConnection->createQueryBuilder();
        $carQuery->getRestrictions()->removeAll();

        $carUids = $carQuery
            ->select('uid_foreign')
            ->from('tx_firefighter_station_car_mm')
            ->where(
                $carQuery->expr()->in('uid_local', $carQuery->createNamedParameter($stationUids, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY))
            )
            ->executeQuery()
            ->fetchFirstColumn();

        if (empty($carUids)) {
            return;
        }

        // Fahrzeuge aus der Haupttabelle abrufen
        $mainConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_domain_model_car');

        $mainQuery = $mainConnection->createQueryBuilder();
        $mainQuery->getRestrictions()->removeAll();

        $cars = $mainQuery
            ->select('uid', 'name')
            ->from('tx_firefighter_domain_model_car')
            ->where(
                $mainQuery->expr()->in('uid', $mainQuery->createNamedParameter($carUids, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY))
            )
            ->orderBy('name', 'ASC')
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($cars as $car) {
            $config['items'][] = [$car['name'], $car['uid']];
        }
    }
}