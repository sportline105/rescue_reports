<?php

namespace In2code\Firefighter\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class StationLabelUtility
{
    public function addGroupedStations(array &$config): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_domain_model_station');

        $queryBuilder = $connection->createQueryBuilder();
        $stations = $queryBuilder
            ->select('uid', 'name', 'brigade')
            ->from('tx_firefighter_domain_model_station')
            ->executeQuery()
            ->fetchAllAssociative();

        $brigadeNames = $this->getBrigadeNames();

        $grouped = [];

        foreach ($stations as $station) {
            $brigade = $brigadeNames[$station['brigade']] ?? 'Unbekannte Feuerwehr';
            $grouped[$brigade][] = [$station['name'], $station['uid']];
        }

        // 🔠 Sortiere Stationen alphabetisch innerhalb jeder Brigade
        foreach ($grouped as &$items) {
            usort($items, fn($a, $b) => strcasecmp($a[0], $b[0]));
        }
        unset($items);

        // 🔢 Optional: Brigade-Gruppen alphabetisch sortieren
        ksort($grouped);

        foreach ($grouped as $brigade => $items) {
            $config['items'][] = [$brigade, '--div--'];
            foreach ($items as $item) {
                $config['items'][] = $item;
            }
        }
    }

    protected function getBrigadeNames(): array
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_domain_model_brigade');

        $queryBuilder = $connection->createQueryBuilder();
        $rows = $queryBuilder
            ->select('uid', 'name')
            ->from('tx_firefighter_domain_model_brigade')
            ->orderBy('name') // Alternativ: ->orderBy('sorting')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_column($rows, 'name', 'uid');
    }
}
