<?php
declare(strict_types=1);
namespace nkfire\RescueReports\Utility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CarLabelItemsProcFunc
{
    public function addOrganisationToLabel(array &$config): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_car')
            ->createQueryBuilder();

        $rows = $queryBuilder
            ->select(
                'car.uid',
                'car.name',
                'org.uid AS organization_uid',
                'org.abbreviation AS organization_abbreviation',
                'org.name AS organization_name'
            )
            ->from('tx_rescuereports_domain_model_car', 'car')
            ->leftJoin('car', 'tx_rescuereports_domain_model_organisation', 'org', 'car.organization = org.uid')
            ->where(
                $queryBuilder->expr()->eq('car.deleted', $queryBuilder->createNamedParameter(0)),
                $queryBuilder->expr()->eq('car.hidden', $queryBuilder->createNamedParameter(0))
            )
            ->orderBy('org.abbreviation', 'ASC')
            ->addOrderBy('org.name', 'ASC')
            ->addOrderBy('car.name', 'ASC')
            ->executeQuery()
            ->fetchAllAssociative();

        $config['items'] = [];
        $currentGroupLabel = null;

        foreach ($rows as $row) {
            $organizationLabel = trim((string)($row['organization_name'] ?? ''));
            if ($organizationLabel === '') {
                $organizationLabel = trim((string)($row['organization_abbreviation'] ?? ''));
            }
            if ($organizationLabel === '') {
                $organizationLabel = 'Ohne Organisation';
            }

            if ($organizationLabel !== $currentGroupLabel) {
                $config['items'][] = [$organizationLabel, '--div--'];
                $currentGroupLabel = $organizationLabel;
            }

            $config['items'][] = [
                (string)$row['name'],
                (int)$row['uid'],
            ];
        }
    }
}
