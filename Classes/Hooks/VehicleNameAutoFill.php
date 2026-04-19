<?php
declare(strict_types=1);

namespace nkfire\RescueReports\Hooks;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class VehicleNameAutoFill
{
    /**
     * Hook called before database operations
     * Modifies fieldArray to auto-fill vehicle name from selected car
     */
    public function processDatamap_postProcessFieldArray(
        string $status,
        string $table,
        $id,
        array &$fieldArray,
        DataHandler $pObj
    ): void {
        if ($table !== 'tx_rescuereports_domain_model_vehicle') {
            return;
        }

        // New record: always auto-fill name from car
        if ($status === 'new' && isset($fieldArray['car'])) {
            $carUid = (int)$fieldArray['car'];
            if ($carUid > 0) {
                $carName = $this->getCarName($carUid);
                if ($carName !== '') {
                    $fieldArray['name'] = $carName;
                }
            }
            return;
        }

        // Update: only auto-fill if car field is being changed
        if ($status !== 'new' && isset($fieldArray['car'])) {
            $newCarUid = (int)$fieldArray['car'];
            $currentCarUid = $this->getCurrentCarUid($id);

            // Only update name if car value actually changed
            if ($newCarUid > 0 && $newCarUid !== $currentCarUid) {
                $carName = $this->getCarName($newCarUid);
                if ($carName !== '') {
                    $fieldArray['name'] = $carName;
                }
            }
        }
    }

    private function getCarName(int $carUid): string
    {
        if ($carUid <= 0) {
            return '';
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_car');

        $row = $connection->select(
            ['name'],
            'tx_rescuereports_domain_model_car',
            ['uid' => $carUid]
        )->fetchAssociative();

        return trim((string)($row['name'] ?? ''));
    }

    private function getCurrentCarUid(int|string $vehicleUid): int
    {
        $vehicleUid = (int)$vehicleUid;
        if ($vehicleUid <= 0) {
            return 0;
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_vehicle');

        $row = $connection->select(
            ['car'],
            'tx_rescuereports_domain_model_vehicle',
            ['uid' => $vehicleUid]
        )->fetchAssociative();

        return (int)($row['car'] ?? 0);
    }
}
