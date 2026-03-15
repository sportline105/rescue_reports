<?php
namespace In2code\RescueReports\Utility;

use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CarLabelUtility
{
    public function getCustomLabel(array &$params, $ref = null): void
    {
        $row = $params['row'];

        $carName = $row['name'] ?? '';
        $orgAbbr = '';

        $organization = $row['organization'] ?? 0;

        // TYPO3 FormEngine liefert bei neuen Datensätzen manchmal Arrays
        if (is_array($organization)) {
            $organization = (int)($organization[0] ?? 0);
        } else {
            $organization = (int)$organization;
        }

        if ($organization > 0) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_rescuereports_domain_model_organisation')
                ->createQueryBuilder();

            $orgRow = $queryBuilder
                ->select('abbreviation')
                ->from('tx_rescuereports_domain_model_organisation')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($organization, ParameterType::INTEGER)
                    )
                )
                ->executeQuery()
                ->fetchAssociative();

            if (!empty($orgRow['abbreviation'])) {
                $orgAbbr = $orgRow['abbreviation'];
            }
        }

        $params['title'] = $orgAbbr ? $carName . ' (' . $orgAbbr . ')' : $carName;
    }
}