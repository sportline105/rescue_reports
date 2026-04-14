<?php
declare(strict_types=1);

namespace nkfire\RescueReports\Hooks;

use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class VehicleNameAutoFill
{
    /**
     * Hook called after database operations
     * Auto-fills the vehicle name field when a car type is selected
     */
    public function processDatamap_afterDatabaseOperations(
        string $status,
        string $table,
        $id,
        array &$fieldArray,
        DataHandler $pObj
    ): void {
        // Only handle vehicle records
        if ($table !== 'tx_rescuereports_domain_model_vehicle') {
            return;
        }

        // Only process when a new record is created or car field is updated
        if ($status !== 'new' && !isset($fieldArray['car'])) {
            return;
        }

        // Get the real UID (for new records, translate temp ID to real UID)
        $realUid = $status === 'new' ? ($pObj->substNEWwithIDs[$id] ?? 0) : (int)$id;

        if ($realUid <= 0) {
            return;
        }

        // Determine which car UID to use
        $carUid = 0;

        if ($status === 'new' && isset($fieldArray['car'])) {
            // For new records, use the car value from fieldArray
            $carUid = (int)$fieldArray['car'];
        } elseif ($status !== 'new') {
            // For updates, first check if car field is being updated
            if (isset($fieldArray['car'])) {
                $carUid = (int)$fieldArray['car'];
            } else {
                // Otherwise get the current car value from database
                $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getConnectionForTable('tx_rescuereports_domain_model_vehicle');

                $vehicleRow = $connection->select(
                    ['car'],
                    'tx_rescuereports_domain_model_vehicle',
                    ['uid' => $realUid]
                )->fetchAssociative();

                if ($vehicleRow) {
                    $carUid = (int)$vehicleRow['car'];
                }
            }
        }

        if ($carUid <= 0) {
            return;
        }

        // Get car name from car record
        $carConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_car');

        $carRow = $carConnection->select(
            ['name'],
            'tx_rescuereports_domain_model_car',
            ['uid' => $carUid]
        )->fetchAssociative();

        if (!$carRow || empty($carRow['name'])) {
            return;
        }

        // Update vehicle name and timestamp
        $vehicleConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_vehicle');

        $vehicleConnection->update(
            'tx_rescuereports_domain_model_vehicle',
            [
                'name' => $carRow['name'],
                'tstamp' => time(),
            ],
            ['uid' => $realUid]
        );
    }
}
