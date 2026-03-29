<?php
declare(strict_types=1);

namespace nkfire\RescueReports\Hooks;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class VehicleNameAutoFill
{
    public function processDatamap_afterDatabaseOperations($status, $table, $id, array &$fieldArray, DataHandler $pObj): void
    {
        // DEBUG: log every call to this hook
        $logFile = \TYPO3\CMS\Core\Core\Environment::getVarPath() . '/log/vehicle_autofill_debug.log';
        $logEntry = date('Y-m-d H:i:s') . " | status=$status | table=$table | id=$id | fieldArray=" . json_encode($fieldArray) . "\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);

        if ($table !== 'tx_rescuereports_domain_model_vehicle') {
            return;
        }

        if ($status === 'new') {
            $realUid = (int)($pObj->substNEWwithIDs[$id] ?? 0);
        } else {
            $realUid = (int)$id;
        }

        file_put_contents($logFile, "  → realUid=$realUid\n", FILE_APPEND);

        if ($realUid <= 0) {
            file_put_contents($logFile, "  → ABBRUCH: realUid <= 0\n", FILE_APPEND);
            return;
        }

        $carUid = !empty($fieldArray['car']) ? (int)$fieldArray['car'] : 0;

        if ($carUid === 0) {
            $existingRow = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_rescuereports_domain_model_vehicle')
                ->select(['car', 'name'], 'tx_rescuereports_domain_model_vehicle', ['uid' => $realUid])
                ->fetchAssociative();

            file_put_contents($logFile, "  → existingRow=" . json_encode($existingRow) . "\n", FILE_APPEND);

            $carUid = (int)($existingRow['car'] ?? 0);

            if (!empty($existingRow['name'])) {
                file_put_contents($logFile, "  → ABBRUCH: name bereits gefüllt\n", FILE_APPEND);
                return;
            }
        }

        file_put_contents($logFile, "  → carUid=$carUid\n", FILE_APPEND);

        if ($carUid === 0) {
            file_put_contents($logFile, "  → ABBRUCH: carUid=0\n", FILE_APPEND);
            return;
        }

        $carRow = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_car')
            ->select(['name'], 'tx_rescuereports_domain_model_car', ['uid' => $carUid])
            ->fetchAssociative();

        file_put_contents($logFile, "  → carRow=" . json_encode($carRow) . "\n", FILE_APPEND);

        if (!empty($carRow['name'])) {
            GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_rescuereports_domain_model_vehicle')
                ->update('tx_rescuereports_domain_model_vehicle', ['name' => $carRow['name']], ['uid' => $realUid]);

            file_put_contents($logFile, "  → ERFOLG: name gesetzt auf '" . $carRow['name'] . "'\n", FILE_APPEND);
        }
    }
}