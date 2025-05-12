<?php
namespace In2code\RescueReports\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class CarFilterUtility
{
    public function filterBySelectedStations(array &$config)
    {
        // Debug: Anfangs-Config
        DebugUtility::debug($config, 'Eingehendes $config');

        $selectedStationUids = $config['row']['stations'] ?? [];

        if (!is_array($selectedStationUids)) {
            $selectedStationUids = GeneralUtility::intExplode(',', $selectedStationUids, true);
        }

        DebugUtility::debug($selectedStationUids, 'Station-UIDs');

        $config['items'] = [];

        if (!empty($selectedStationUids)) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_rescuereports_station_car_mm');

            $rows = $queryBuilder
                ->select('uid_local', 'uid_foreign')
                ->from('tx_rescuereports_station_car_mm')
                ->where(
                    $queryBuilder->expr()->in(
                        'uid_local',
                        $queryBuilder->createNamedParameter($selectedStationUids, \TYPO3\CMS\Core\Database\Connection::PARAM_INT_ARRAY)
                    )
                )
                ->executeQuery()
                ->fetchAllAssociative();

            DebugUtility::debug($rows, 'Station-Car-Zuordnungen');

            // Fahrzeuge nach Brigade gruppieren
            $grouped = [];
            foreach ($rows as $row) {
                $stationUid = (int)$row['uid_local'];
                $carUid = (int)$row['uid_foreign'];

                // Station holen
                $stationQuery = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getQueryBuilderForTable('tx_rescuereports_domain_model_station');
                $station = $stationQuery
                    ->select('uid', 'name', 'brigade')
                    ->from('tx_rescuereports_domain_model_station')
                    ->where(
                        $stationQuery->expr()->eq('uid', $stationQuery->createNamedParameter($stationUid, \PDO::PARAM_INT))
                    )
                    ->executeQuery()
                    ->fetchAssociative();

                DebugUtility::debug($station, 'Station');

                // Brigade holen
                $brigadeName = '';
                if (!empty($station['brigade'])) {
                    $brigadeQuery = GeneralUtility::makeInstance(ConnectionPool::class)
                        ->getQueryBuilderForTable('tx_rescuereports_domain_model_brigade');
                    $brigade = $brigadeQuery
                        ->select('uid', 'name')
                        ->from('tx_rescuereports_domain_model_brigade')
                        ->where(
                            $brigadeQuery->expr()->eq('uid', $brigadeQuery->createNamedParameter($station['brigade'], \PDO::PARAM_INT))
                        )
                        ->executeQuery()
                        ->fetchAssociative();
                    DebugUtility::debug($brigade, 'Brigade');
                    $brigadeName = $brigade['name'] ?? '';
                }

                // Fahrzeug holen
                $carQuery = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getQueryBuilderForTable('tx_rescuereports_domain_model_car');
                $car = $carQuery
                    ->select('uid', 'name')
                    ->from('tx_rescuereports_domain_model_car')
                    ->where(
                        $carQuery->expr()->eq('uid', $carQuery->createNamedParameter($carUid, \PDO::PARAM_INT))
                    )
                    ->executeQuery()
                    ->fetchAssociative();

                DebugUtility::debug($car, 'Fahrzeug');

                if ($car && $station && $brigadeName) {
                    $label = $station['name'] . ' – ' . $car['name'];
                    $grouped[$brigadeName][] = [$label, $car['uid'] . '__' . $station['uid']];
                }
            }

            //DebugUtility::debug($grouped, 'Gruppierte Fahrzeuge nach Brigade');

            // Sortierung nach Brigade-Name (optional)
            ksort($grouped);

            // Items-Array mit Dividern aufbauen
            foreach ($grouped as $brigadeName => $items) {
                if (trim($brigadeName) !== '') {
                    $config['items'][] = ['--div--' => $brigadeName]; // Divider korrekt!
                }
                foreach ($items as $item) {
                    $config['items'][] = $item;
                }
            }
        }

        // Debug: Ergebnis-Items
        //DebugUtility::debug($config['items'], 'Fertige Items für das Select-Feld');
    }
}