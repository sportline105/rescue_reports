<?php

declare(strict_types=1);

namespace nkfire\RescueReports\EventListener;

use Doctrine\DBAL\ParameterType;
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

        $vehicleQueryBuilder = $vehicleConnection->createQueryBuilder();
        $vehicleRow = $vehicleQueryBuilder
            ->select('car', 'name')
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

        $currentName = trim((string)($vehicleRow['name'] ?? ''));
        if ($currentName !== '') {
            return;
        }

        $carName = $this->getCarNameByUid((int)$vehicleRow['car']);
        if ($carName === '') {
            return;
        }

        $vehicleConnection->update(
            'tx_rescuereports_domain_model_vehicle',
            [
                'name' => $carName,
                'tstamp' => time(),
            ],
            ['uid' => $recordUid]
        );
    }

    private function getCarNameByUid(int $carUid): string
    {
        if ($carUid <= 0) {
            return '';
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_rescuereports_domain_model_car');

        $row = $queryBuilder
            ->select('name')
            ->from('tx_rescuereports_domain_model_car')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($carUid, ParameterType::INTEGER)
                ),
                $queryBuilder->expr()->eq(
                    'deleted',
                    $queryBuilder->createNamedParameter(0, ParameterType::INTEGER)
                )
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        return trim((string)($row['name'] ?? ''));
    }
}
