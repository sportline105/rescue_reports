<?php
declare(strict_types=1);
namespace nkfire\RescueReports\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class StationRepository extends Repository
{
    public function findPrimaryBrigadeStations()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching(
            $query->logicalAnd(
                $query->equals('brigade.isPrimary', true),
                $query->equals('excludeFromFilter', false)
            )
        );

        $query->setOrderings([
            'sorting' => QueryInterface::ORDER_ASCENDING,
            'name' => QueryInterface::ORDER_ASCENDING,
        ]);

        return $query->execute();
    }

    public function findByPrefixAndPrimaryBrigade(string $prefix): ?\nkfire\RescueReports\Domain\Model\Station
    {
        if (empty($prefix)) {
            return null;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_rescuereports_domain_model_station');

        $row = $queryBuilder
            ->select('s.uid')
            ->from('tx_rescuereports_domain_model_station', 's')
            ->join('s', 'tx_rescuereports_domain_model_brigade', 'b', 's.brigade = b.uid')
            ->where(
                $queryBuilder->expr()->eq('b.is_primary', $queryBuilder->createNamedParameter(1, \Doctrine\DBAL\ParameterType::INTEGER)),
                $queryBuilder->expr()->eq('s.deleted', $queryBuilder->createNamedParameter(0, \Doctrine\DBAL\ParameterType::INTEGER)),
                $queryBuilder->expr()->eq('s.hidden', $queryBuilder->createNamedParameter(0, \Doctrine\DBAL\ParameterType::INTEGER)),
                'LOWER(s.prefix) = ' . $queryBuilder->createNamedParameter(mb_strtolower($prefix))
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        if (!$row) {
            return null;
        }

        return $this->findByUid((int)$row['uid']);
    }
}