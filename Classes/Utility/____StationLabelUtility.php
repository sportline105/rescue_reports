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

        $brigadeData = $this->getBrigadeNamesAndPriorities();

        $grouped = [];

        foreach ($stations as $station) {
            $brigadeName = $brigadeData[$station['brigade']]['name'] ?? 'Unbekannte Feuerwehr';
            $priority = $brigadeData[$station['brigade']]['priority'] ?? 0;
            $groupKey = str_pad((string)$priority, 5, '0', STR_PAD_LEFT) . '_' . $brigadeName;
            $grouped[$groupKey][] = [$station['name'], $station['uid']];
        }

        // 🔠 Sortiere Stationen alphabetisch innerhalb jeder Brigade
        foreach ($grouped as &$items) {
            usort($items, fn($a, $b) => strcasecmp($a[0], $b[0]));
        }
        unset($items);

        // 🔢 Sortiere Gruppen nach Priorität und Brigade-Name
        ksort($grouped);

        foreach ($grouped as $brigade => $items) {
            $config['items'][] = [explode('_', $brigade, 2)[1], '--div--'];
            foreach ($items as $item) {
                $config['items'][] = $item;
            }
        }
    }

    protected function getBrigadeNamesAndPriorities(): array
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_firefighter_domain_model_brigade');

        $queryBuilder = $connection->createQueryBuilder();
        $rows = $queryBuilder
            ->select('uid', 'name', 'priority')
            ->from('tx_firefighter_domain_model_brigade')
            ->orderBy('priority', 'ASC')
            ->addOrderBy('name', 'ASC')
            ->executeQuery()
            ->fetchAllAssociative();

        $brigadeData = [];
        foreach ($rows as $row) {
            $brigadeData[$row['uid']] = [
                'name' => $row['name'],
                'priority' => (int)$row['priority']
            ];
        }

        return $brigadeData;
    }
}
