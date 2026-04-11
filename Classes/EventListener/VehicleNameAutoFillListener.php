<?php
declare(strict_types=1);

namespace nkfire\RescueReports\EventListener;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\DataHandling\Event\AfterRecordCreatedEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class VehicleNameAutoFillListener
{
    public function __invoke(AfterRecordCreatedEvent $event): void
    {
        if ($event->getTableName() !== 'tx_rescuereports_domain_model_vehicle') {
            return;
        }

        $recordUid = $event->getRecordUid();
        $fields = $event->getRecord();

        if (!empty($fields['car']) && $recordUid > 0) {
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_rescuereports_domain_model_car');

            $carRow = $connection->select(
                ['name'],
                'tx_rescuereports_domain_model_car',
                ['uid' => (int)$fields['car']]
            )->fetchAssociative();

            if (!empty($carRow['name'])) {
                GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getConnectionForTable('tx_rescuereports_domain_model_vehicle')
                    ->update(
                        'tx_rescuereports_domain_model_vehicle',
                        ['name' => $carRow['name']],
                        ['uid' => $recordUid]
                    );
            }
        }
    }
}
