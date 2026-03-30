<?php
declare(strict_types=1);

namespace nkfire\RescueReports\Utility;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CarFilterUtility
{
    public function filterBySelectedStations(array &$config): void
    {
        $selectedStationUids = $config['row']['stations'] ?? [];
        if (!is_array($selectedStationUids)) {
            $selectedStationUids = GeneralUtility::intExplode(',', (string)$selectedStationUids, true);
        } else {
            $selectedStationUids = array_map('intval', $selectedStationUids);
        }

        $config['items'] = [];
        $alreadyAddedCarUids = [];

        if ($selectedStationUids !== []) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_rescuereports_station_car_mm');

            $rows = $queryBuilder
                ->select('uid_local', 'uid_foreign')
                ->from('tx_rescuereports_station_car_mm')
                ->where(
                    $queryBuilder->expr()->in(
                        'uid_local',
                        $queryBuilder->createNamedParameter($selectedStationUids, ArrayParameterType::INTEGER)
                    )
                )
                ->executeQuery()
                ->fetchAllAssociative();

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
                        $stationQuery->expr()->eq(
                            'uid',
                            $stationQuery->createNamedParameter($stationUid, ParameterType::INTEGER)
                        )
                    )
                    ->executeQuery()
                    ->fetchAssociative();

                if (!$station) {
                    continue;
                }

                $brigadeName = '';
                $brigadeSorting = 999999;
                $brigadeUid = 0;

                if (!empty($station['brigade'])) {
                    $brigadeQuery = GeneralUtility::makeInstance(ConnectionPool::class)
                        ->getQueryBuilderForTable('tx_rescuereports_domain_model_brigade');

                    $brigade = $brigadeQuery
                        ->select('uid', 'name', 'sorting')
                        ->from('tx_rescuereports_domain_model_brigade')
                        ->where(
                            $brigadeQuery->expr()->eq(
                                'uid',
                                $brigadeQuery->createNamedParameter((int)$station['brigade'], ParameterType::INTEGER)
                            )
                        )
                        ->executeQuery()
                        ->fetchAssociative();

                    $brigadeName = $brigade['name'] ?? '';
                    $brigadeSorting = (int)($brigade['sorting'] ?? 999999);
                    $brigadeUid = (int)($brigade['uid'] ?? 0);
                }

                $carQuery = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getQueryBuilderForTable('tx_rescuereports_domain_model_car');

                $car = $carQuery
                    ->select('uid', 'name')
                    ->from('tx_rescuereports_domain_model_car')
                    ->where(
                        $carQuery->expr()->eq(
                            'uid',
                            $carQuery->createNamedParameter($carUid, ParameterType::INTEGER)
                        )
                    )
                    ->executeQuery()
                    ->fetchAssociative();

                if ($car && $brigadeName !== '') {
                    $label = '  🚒 ' . $car['name'];
                    $value = (int)$car['uid'];

                    $alreadyAddedCarUids[] = $value;
                    $grouped[$brigadeUid]['sorting'] = $brigadeSorting;
                    $grouped[$brigadeUid]['name'] = $brigadeName;
                    $grouped[$brigadeUid]['stations'][$stationUid]['name'] = $station['name'];
                    $grouped[$brigadeUid]['stations'][$stationUid]['sorting'] = $station['sorting'];
                    $grouped[$brigadeUid]['stations'][$stationUid]['cars'][] = [$label, $value];
                }
            }

            uasort($grouped, static function (array $a, array $b): int {
                return ($a['sorting'] ?? 999999) <=> ($b['sorting'] ?? 999999);
            });

            foreach ($grouped as $brigadeArr) {
                if (trim((string)($brigadeArr['name'] ?? '')) !== '') {
                    $config['items'][] = [
                        '🏘️ ' . $brigadeArr['name'],
                        null,
                        null,
                        null,
                        'divider',
                    ];
                }

                $stations = $brigadeArr['stations'] ?? [];
                uasort($stations, static function (array $a, array $b): int {
                    return ($a['sorting'] ?? 9999) <=> ($b['sorting'] ?? 9999);
                });

                foreach ($stations as $station) {
                    if (!empty($station['name'])) {
                        $config['items'][] = [
                            '📍 ' . $station['name'],
                            null,
                            null,
                            null,
                            'divider',
                        ];
                    }

                    $cars = $station['cars'] ?? [];
                    usort($cars, static function (array $a, array $b): int {
                        return strnatcasecmp((string)$a[0], (string)$b[0]);
                    });

                    foreach ($cars as $item) {
                        $config['items'][] = [$item[0], $item[1]];
                    }
                }
            }
        }

        $selectedCarUids = [];
        if (!empty($config['row']['cars'])) {
            if (is_array($config['row']['cars'])) {
                $selectedCarUids = array_map('intval', $config['row']['cars']);
            } else {
                $selectedCarUids = GeneralUtility::intExplode(',', (string)$config['row']['cars'], true);
            }
        }

        foreach ($selectedCarUids as $carUid) {
            if (!in_array($carUid, $alreadyAddedCarUids, true)) {
                $carQuery = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getQueryBuilderForTable('tx_rescuereports_domain_model_car');

                $car = $carQuery
                    ->select('uid', 'name')
                    ->from('tx_rescuereports_domain_model_car')
                    ->where(
                        $carQuery->expr()->eq(
                            'uid',
                            $carQuery->createNamedParameter((int)$carUid, ParameterType::INTEGER)
                        )
                    )
                    ->executeQuery()
                    ->fetchAssociative();

                if ($car) {
                    $label = '  🚒 ' . $car['name'] . ' (nicht mehr auswählbar)';
                    $value = (int)$car['uid'];
                    $config['items'][] = [$label, $value];
                }
            }
        }
    }
}