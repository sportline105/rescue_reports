<?php

namespace In2code\Firefighter\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class EventVehicleAssignmentUtility
{
    /**
     * Diese Funktion wird per itemsProcFunc im Feld "cars" des EventVehicleAssignment aufgerufen,
     * um nur Fahrzeuge aus den im Event ausgewählten Stationen anzuzeigen.
     */
    public function addStationCarsToAssignment(array &$config): void
    {
        // Rückfall-Wert, falls keine Relation existiert
        $config['items'] = [];

        // UID des aktuellen Datensatzes (event_vehicle_assignment)
        $assignmentRow = $config['row'] ?? [];
        if (empty($assignmentRow['uid']) || empty($assignmentRow['station'])) {
            return;
        }

        $stationUid = (int)$assignmentRow['station'];

        // Hole Fahrzeuge der Station aus MM-Tabelle
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_station_car_mm');

        $queryBuilder = $connection->createQueryBuilder();
        $carUids = $queryBuilder
            ->select('uid_foreign')
            ->from('tx_firefighter_station_car_mm')
            ->where(
                $queryBuilder->expr()->eq('uid_local', $queryBuilder->createNamedParameter($stationUid, \PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchFirstColumn();

        if (empty($carUids)) {
            return;
        }

        // Fahrzeuge aus Haupttabelle abrufen
        $carConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_domain_model_car');

        $carQuery = $carConnection->createQueryBuilder();
        $carQuery->getRestrictions()->removeAll();

        $cars = $carQuery
            ->select('uid', 'name')
            ->from('tx_firefighter_domain_model_car')
            ->where(
                $carQuery->expr()->in('uid', $carQuery->createNamedParameter($carUids, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY))
            )
            ->orderBy('name', 'ASC')
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($cars as $car) {
            $config['items'][] = [$car['name'], $car['uid']];
        }
    }
}
