<?php
declare(strict_types=1);

namespace nkfire\RescueReports\EventListener;

use TYPO3\CMS\Core\DataHandling\Event\AfterRecordCreatedEvent;
use TYPO3\CMS\Core\DataHandling\Event\AfterRecordUpdatedEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class VehicleNameAutoFillListener
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

    protected function updateVehicleName(int $recordUid): void
    {
        if ($recordUid <= 0) {
            return;
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_vehicle');

        // Get the vehicle's car field
        $vehicleRow = $connection->select(
            ['car'],
            'tx_rescuereports_domain_model_vehicle',
            ['uid' => $recordUid]
        )->fetchAssociative();

        if (empty($vehicleRow) || empty($vehicleRow['car'])) {
            return;
        }

        // Get car name
        $carConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_car');

        $carRow = $carConnection->select(
            ['name'],
            'tx_rescuereports_domain_model_car',
            ['uid' => (int)$vehicleRow['car']]
        )->fetchAssociative();

        if (empty($carRow['name'])) {
            return;
        }

        // Update vehicle name
        $connection->update(
            'tx_rescuereports_domain_model_vehicle',
            ['name' => $carRow['name']],
            ['uid' => $recordUid]
        );
    }
}
