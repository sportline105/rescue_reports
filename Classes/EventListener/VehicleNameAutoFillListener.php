<?php

declare(strict_types=1);

namespace nkfire\RescueReports\EventListener;

use TYPO3\CMS\Core\DataHandling\Event\AfterRecordCreatedEvent;
use TYPO3\CMS\Core\DataHandling\Event\AfterRecordUpdatedEvent;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class VehicleNameAutoFillListener
{
    public function onAfterRecordCreated(AfterRecordCreatedEvent $event): void
    {
        if ($event->getTableName() !== 'tx_rescuereports_domain_model_vehicle') {
            return;
        }

        $recordUid = $event->getRecordUid();
        $this->updateVehicleName($recordUid);
    }

    public function onAfterRecordUpdated(AfterRecordUpdatedEvent $event): void
    {
        if ($event->getTableName() !== 'tx_rescuereports_domain_model_vehicle') {
            return;
        }

        $recordUid = $event->getRecordUid();
        $this->updateVehicleName($recordUid);
    }

    private function updateVehicleName(int $recordUid): void
    {
        if ($recordUid <= 0) {
            return;
        }

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $vehicleConnection = $connectionPool->getConnectionForTable('tx_rescuereports_domain_model_vehicle');

        $vehicleRow = $vehicleConnection->select(
            ['car'],
            'tx_rescuereports_domain_model_vehicle',
            ['uid' => $recordUid]
        )->fetchAssociative();

        if (!$vehicleRow || empty($vehicleRow['car'])) {
            return;
        }

        $carConnection = $connectionPool->getConnectionForTable('tx_rescuereports_domain_model_car');

        $carRow = $carConnection->select(
            ['name'],
            'tx_rescuereports_domain_model_car',
            ['uid' => (int)$vehicleRow['car']]
        )->fetchAssociative();

        if (!$carRow || empty($carRow['name'])) {
            return;
        }

        $vehicleConnection->update(
            'tx_rescuereports_domain_model_vehicle',
            [
                'name' => $carRow['name'],
                'tstamp' => time(),
            ],
            ['uid' => $recordUid]
        );
    }
}
