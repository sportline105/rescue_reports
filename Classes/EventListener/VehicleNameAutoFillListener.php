<?php

declare(strict_types=1);

namespace nkfire\RescueReports\EventListener;

use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\DataHandling\Event\BeforeRecordCreatedEvent;
use TYPO3\CMS\Core\DataHandling\Event\BeforeRecordUpdatedEvent;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class VehicleNameAutoFillListener
{
    public function beforeRecordCreated(BeforeRecordCreatedEvent $event): void
    {
        if ($event->getTableName() !== 'tx_rescuereports_domain_model_vehicle') {
            return;
        }

        $record = $event->getRecord();
        $record = $this->processCreate($record);
        $event->setRecord($record);
    }

    public function beforeRecordUpdated(BeforeRecordUpdatedEvent $event): void
    {
        if ($event->getTableName() !== 'tx_rescuereports_domain_model_vehicle') {
            return;
        }

        $record = $event->getRecord();
        $record = $this->processUpdate($record, $event->getUid());
        $event->setRecord($record);
    }

    private function processCreate(array $fieldArray): array
    {
        $currentName = trim((string)($fieldArray['name'] ?? ''));
        if ($currentName !== '') {
            return $fieldArray;
        }

        $carUid = (int)($fieldArray['car'] ?? 0);
        if ($carUid <= 0) {
            return $fieldArray;
        }

        $carName = $this->getCarNameByUid($carUid);
        if ($carName === '') {
            return $fieldArray;
        }

        $fieldArray['name'] = $carName;
        return $fieldArray;
    }

    private function processUpdate(array $fieldArray, int|string $id): array
    {
        // Nur reagieren, wenn car im aktuellen Speichervorgang überhaupt mitkommt
        if (!array_key_exists('car', $fieldArray)) {
            return $fieldArray;
        }

        $newCarUid = (int)$fieldArray['car'];
        if ($newCarUid <= 0) {
            return $fieldArray;
        }

        $currentCarUid = (int)$this->getCurrentVehicleCarUid((int)$id);
        if ($newCarUid === $currentCarUid) {
            return $fieldArray;
        }

        $carName = $this->getCarNameByUid($newCarUid);
        if ($carName === '') {
            return $fieldArray;
        }

        // Beim Update nur überschreiben, wenn car wirklich geändert wurde
        $fieldArray['name'] = $carName;
        return $fieldArray;
    }

    private function getCurrentVehicleCarUid(int $vehicleUid): int
    {
        if ($vehicleUid <= 0) {
            return 0;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_rescuereports_domain_model_vehicle');

        $row = $queryBuilder
            ->select('car')
            ->from('tx_rescuereports_domain_model_vehicle')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($vehicleUid, ParameterType::INTEGER)
                )
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        return (int)($row['car'] ?? 0);
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
