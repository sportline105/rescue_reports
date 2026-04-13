<?php
declare(strict_types=1);
namespace nkfire\RescueReports\Utility;
use Doctrine\DBAL\ArrayParameterType;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;

class TcaProcFunc
{
    public function filterCarsByStationsUsingMM(array &$config)
    {
        $eventRow = $config['row'];

        // Sicherheitscheck: Gibt es Stationen?
        if (empty($eventRow['stations'])) {
            return;
        }

        $stationIds = is_array($eventRow['stations'])
        ? array_map('intval', $eventRow['stations'])
        : GeneralUtility::intExplode(',', (string)$eventRow['stations'], true);

        if (empty($stationIds)) {
            return;
        }

        /** @var Connection $connection */
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_station_car_mm');

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder
            ->select('car.uid', 'car.name')
            ->from('tx_rescuereports_station_car_mm', 'mm')
            ->innerJoin('mm', 'tx_rescuereports_domain_model_car', 'car', 'car.uid = mm.uid_foreign')
            ->where(
                $queryBuilder->expr()->in('mm.uid_local', $queryBuilder->createNamedParameter($stationIds, ArrayParameterType::INTEGER))
            )
            ->groupBy('car.uid')
            ->orderBy('car.name');

        $cars = $queryBuilder->executeQuery()->fetchAllAssociative();

        foreach ($cars as $car) {
            $config['items'][] = [
                sprintf('%s (ID %d)', $car['name'], $car['uid']),
                (int)$car['uid']
            ];
        }
    }
}