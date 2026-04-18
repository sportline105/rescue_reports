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

        $this->process($event->getRecord());
    }

    public function beforeRecordUpdated(BeforeRecordUpdatedEvent $event): void
    {
        if ($event->getTableName() !== 'tx_rescuereports_domain_model_vehicle') {
            return;
        }

        $this->process($event->getRecord());
    }

    private function process(array &$fieldArray): void
    {
        // Nur wenn car gesetzt wurde
        if (!isset($fieldArray['car'])) {
            return;
        }

        $carUid = (int)$fieldArray['car'];
        if ($carUid <= 0) {
            return;
        }

        // 👉 Optional: nur wenn name leer ist (wie früher sinnvoll)
        if (!empty($fieldArray['name'])) {
            return;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_rescuereports_domain_model_car');

        $car = $queryBuilder
            ->select('name')
            ->from('tx_rescuereports_domain_model_car')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($carUid, ParameterType::INTEGER)
                )
            )
            ->executeQuery()
            ->fetchAssociative();

        if (!empty($car['name'])) {
            $fieldArray['name'] = (string)$car['name'];
        }
    }
}                $carQueryBuilder->expr()->eq(
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
