<?php

namespace In2code\Firefighter\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;

class EventVehicleAssignmentUtility
{
    /**
     * itemsProcFunc for field "Fahrzeugeinsatz"
     * Dynamisch Fahrzeuge basierend auf gewählten Stationen im Event-Datensatz laden.
     *
     * @param array \$config
     */
    public function getAssignmentOptions(array &\$config): void
    {
        // Holen der aktuellen Record UID aus dem Parent config
        \$eventUid = (int)(\$config['row']['uid'] ?? 0);
        if (\$eventUid === 0) {
            return;
        }

        // Verbindung zur Event-Tabelle
        \$eventConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_domain_model_event');

        // UID der zugeordneten Station(en) laden (angenommen Feld heißt "station")
        \$stationIdString = \$eventConnection->select([
                'station'
            ],
            'tx_firefighter_domain_model_event',
            ['uid' => \$eventUid]
        )->fetchOne();

        if (empty(\$stationIdString)) {
            return;
        }

        \$stationIds = GeneralUtility::intExplode(',', \$stationIdString, true);

        // Fahrzeuge über die MM-Tabelle laden
        \$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_firefighter_domain_model_car');

        \$queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        \$cars = \$queryBuilder
            ->select('car.uid', 'car.norm')
            ->from('tx_firefighter_domain_model_car', 'car')
            ->leftJoin(
                'car',
                'tx_firefighter_domain_model_car_station_mm',
                'mm',
                'car.uid = mm.uid_local'
            )
            ->where(
                \$queryBuilder->expr()->in(
                    'mm.uid_foreign',
                    \$queryBuilder->createNamedParameter(
                        \$stationIds,
                        Connection::PARAM_INT_ARRAY
                    )
                )
            )
            ->groupBy('car.uid')
            ->executeQuery()
            ->fetchAllAssociative();

        foreach (\$cars as \$car) {
            \$config['items'][] = [\$car['norm'], (int)\$car['uid']];
        }
    }
}