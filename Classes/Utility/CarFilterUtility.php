<?php
namespace In2code\RescueReports\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
//use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class CarFilterUtility
{
    public function filterBySelectedStations(array &$config)
    {
        //DebugUtility::debug($config, 'Eingehendes $config');

        $selectedStationUids = $config['row']['stations'] ?? [];
        if (!is_array($selectedStationUids)) {
            $selectedStationUids = GeneralUtility::intExplode(',', $selectedStationUids, true);
        }
        //DebugUtility::debug($selectedStationUids, 'Station-UIDs');

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

            //DebugUtility::debug($rows, 'Station-Car-Zuordnungen');

            $grouped = [];
            foreach ($rows as $row) {
                $stationUid = (int)$row['uid_local'];
                $carUid = (int)$row['uid_foreign'];

                $stationQuery = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getQueryBuilderForTable('tx_rescuereports_domain_model_station');
                $station = $stationQuery
                    ->select('uid', 'name', 'brigade', 'sorting')
                    ->from('tx_rescuereports_domain_model_station')
                    ->where(
                        $stationQuery->expr()->eq('uid', $stationQuery->createNamedParameter($stationUid, \PDO::PARAM_INT))
                    )
                    ->executeQuery()
                    ->fetchAssociative();

                //DebugUtility::debug($station, 'Station');

                $brigadeName = '';
                $brigadePriority = 9999;
                $brigadeUid = 0;
                if (!empty($station['brigade'])) {
                    $brigadeQuery = GeneralUtility::makeInstance(ConnectionPool::class)
                        ->getQueryBuilderForTable('tx_rescuereports_domain_model_brigade');
                    $brigade = $brigadeQuery
                        ->select('uid', 'name', 'priority')
                        ->from('tx_rescuereports_domain_model_brigade')
                        ->where(
                            $brigadeQuery->expr()->eq('uid', $brigadeQuery->createNamedParameter($station['brigade'], \PDO::PARAM_INT))
                        )
                        ->executeQuery()
                        ->fetchAssociative();
                    //DebugUtility::debug($brigade, 'Brigade');
                    $brigadeName = $brigade['name'] ?? '';
                    $brigadePriority = isset($brigade['priority']) ? (int)$brigade['priority'] : 9999;
                    $brigadeUid = (int)($brigade['uid'] ?? 0);
                }

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

                //DebugUtility::debug($car, 'Fahrzeug');

                if ($car && $station && $brigadeName) {
    				$label = '     🚒 ' . $car['name'];
    				$value = 'fz:' . $car['uid'] . ':' . $station['uid'];
    				$grouped[$brigadeUid]['priority'] = $brigadePriority;
    				$grouped[$brigadeUid]['name'] = $brigadeName;
    				$grouped[$brigadeUid]['stations'][$stationUid]['name'] = $station['name'];
    				$grouped[$brigadeUid]['stations'][$stationUid]['sorting'] = $station['sorting'];
    				$grouped[$brigadeUid]['stations'][$stationUid]['cars'][] = [$label, $value];
				}

            }

            //DebugUtility::debug($grouped, 'Gruppiert: Brigade > Station > Fahrzeuge');

            uasort($grouped, function($a, $b) {
                return $a['priority'] <=> $b['priority'];
            });

            foreach ($grouped as $brigadeArr) {
                if (trim($brigadeArr['name']) !== '') {
                    $config['items'][] = [
                        '🏘️ ' . $brigadeArr['name'],
                        null,
                        null,
                        null,
                        'divider'
                    ];
                }
                $stations = $brigadeArr['stations'];
                uasort($stations, function($a, $b) {
                    return ($a['sorting'] ?? 9999) <=> ($b['sorting'] ?? 9999);
                });
                foreach ($stations as $station) {
                    if (!empty($station['name'])) {
                        $config['items'][] = [
                            '📍 ' . $station['name'],
                            null,
                            null,
                            null,
                            'divider'
                        ];
                    }
                    usort($station['cars'], function($a, $b) {
                        return strnatcasecmp($a[0], $b[0]);
                    });
                    foreach ($station['cars'] as $item) {
                        $config['items'][] = [$item[0], $item[1]];
                    }
                }
            }
        }

        //DebugUtility::debug($config['items'], 'Fertige Items für das Select-Feld');
    }
}