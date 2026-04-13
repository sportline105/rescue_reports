<?php
declare(strict_types=1);

namespace nkfire\RescueReports\EventListener;

use Doctrine\DBAL\ParameterType;
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

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $vehicleConnection = $connectionPool->getConnectionForTable('tx_rescuereports_domain_model_vehicle');

        // Get the vehicle's car field using QueryBuilder
        $vehicleQueryBuilder = $vehicleConnection->createQueryBuilder();
        $vehicleRow = $vehicleQueryBuilder
            ->select('car')
            ->from('tx_rescuereports_domain_model_vehicle')
            ->where(
                $vehicleQueryBuilder->expr()->eq(
                    'uid',
                    $vehicleQueryBuilder->createNamedParameter($recordUid, ParameterType::INTEGER)
                )
            )
            ->executeQuery()
            ->fetchAssociative();

        if (empty($vehicleRow) || empty($vehicleRow['car'])) {
            return;
        }

        // Get car name using QueryBuilder
        $carConnection = $connectionPool->getConnectionForTable('tx_rescuereports_domain_model_car');
        $carQueryBuilder = $carConnection->createQueryBuilder();
        $carRow = $carQueryBuilder
            ->select('name')
            ->from('tx_rescuereports_domain_model_car')
            ->where(
                $carQueryBuilder->expr()->eq(
                    'uid',
                    $carQueryBuilder->createNamedParameter((int)$vehicleRow['car'], ParameterType::INTEGER)
                )
            )
            ->executeQuery()
            ->fetchAssociative();

        if (empty($carRow) || empty($carRow['name'])) {
            return;
        }

        // Update vehicle name and timestamp
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
