<?php
declare(strict_types=1);

namespace nkfire\RescueReports\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\Event\AfterDatabaseOperationsForRecordEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * PSR-14 Event Listener — replaces the deprecated processDatamapClass hook.
 * Active in TYPO3 v14+. For v13, the classic hook in ext_localconf.php is used.
 */
#[AsEventListener(identifier: 'rescue-reports/vehicle-name-autofill')]
final class VehicleNameAutoFillListener
{
    public function __invoke(AfterDatabaseOperationsForRecordEvent $event): void
    {
        if ($event->getTable() !== 'tx_rescuereports_domain_model_vehicle') {
            return;
        }
        if ($event->getStatus() !== 'new') {
            return;
        }

        $fieldArray = $event->getFields();
        $id = $event->getId();
        $dataHandler = $event->getDataHandler();
        $realUid = $dataHandler->substNEWwithIDs[$id] ?? 0;

        if (empty($fieldArray['car']) || $realUid <= 0) {
            return;
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_car');

        $carRow = $connection->select(
            ['name'],
            'tx_rescuereports_domain_model_car',
            ['uid' => (int)$fieldArray['car']]
        )->fetchAssociative();

        if (!empty($carRow['name'])) {
            GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_rescuereports_domain_model_vehicle')
                ->update(
                    'tx_rescuereports_domain_model_vehicle',
                    ['name' => $carRow['name']],
                    ['uid' => $realUid]
                );
        }
    }
}
