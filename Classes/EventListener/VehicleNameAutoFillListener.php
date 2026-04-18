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

        $this->processVehicleData($event->getRecord(), $event->getUid());
    }

    public function beforeRecordUpdated(BeforeRecordUpdatedEvent $event): void
    {
        if ($event->getTableName() !== 'tx_rescuereports_domain_model_vehicle') {
            return;
        }

        $this->processVehicleData($event->getRecord(), $event->getUid());
    }

    protected function processVehicleData(array &$fieldArray, int|string $id): void
    {
        $carUid = $this->resolveCarUid($fieldArray, $id);
        if ($carUid <= 0) {
            return;
        }

        $carName = $this->getCarNameByUid($carUid);
        if ($carName === '') {
            return;
        }

        // Gleiches Verhalten wie der bisherige Hook:
        // Fahrzeugname wird aus dem gewählten car gesetzt/überschrieben.
        $fieldArray['name'] = $carName;
    }

    protected function resolveCarUid(array $fieldArray, int|string $id): int
    {
        // Wenn car im aktuellen Speichervorgang gesetzt/geändert wird, diesen Wert verwenden
        if (!empty($fieldArray['car'])) {
            return (int)$fieldArray['car'];
        }

        // Bei bestehenden Datensätzen auf aktuellen DB-Wert zurückfallen
        if ((int)$id > 0) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_rescuereports_domain_model_vehicle');

            $row = $queryBuilder
                ->select('car')
                ->from('tx_rescuereports_domain_model_vehicle')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter((int)$id, ParameterType::INTEGER)
                    )
                )
                ->setMaxResults(1)
                ->executeQuery()
                ->fetchAssociative();

            return (int)($row['car'] ?? 0);
        }

        return 0;
    }

    protected function getCarNameByUid(int $carUid): string
    {
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
