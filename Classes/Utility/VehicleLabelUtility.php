<?php
declare(strict_types=1);

namespace nkfire\RescueReports\Utility;

use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class VehicleLabelUtility
{
    public function getCustomLabel(array &$params, $ref = null): void
    {
        $row = $params['row'];

        $vehicleName = $row['name'] ?? '';
        $orgAbbr = '';

        $car = $row['car'] ?? 0;

        if (is_array($car)) {
            $car = (int)($car[0] ?? 0);
        } else {
            $car = (int)$car;
        }

        if ($car > 0) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_rescuereports_domain_model_car')
                ->createQueryBuilder();

            $carRow = $queryBuilder
                ->select('organization', 'name')
                ->from('tx_rescuereports_domain_model_car')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($car, ParameterType::INTEGER)
                    )
                )
                ->executeQuery()
                ->fetchAssociative();

            if (!empty($carRow['organization'])) {
                $orgQuery = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getConnectionForTable('tx_rescuereports_domain_model_organisation')
                    ->createQueryBuilder();

                $orgRow = $orgQuery
                    ->select('abbreviation')
                    ->from('tx_rescuereports_domain_model_organisation')
                    ->where(
                        $orgQuery->expr()->eq(
                            'uid',
                            $orgQuery->createNamedParameter((int)$carRow['organization'], ParameterType::INTEGER)
                        )
                    )
                    ->executeQuery()
                    ->fetchAssociative();

                if (!empty($orgRow['abbreviation'])) {
                    $orgAbbr = $orgRow['abbreviation'];
                }
            }
        }

        $params['title'] = $orgAbbr ? $vehicleName . ' (' . $orgAbbr . ')' : $vehicleName;
    }
}