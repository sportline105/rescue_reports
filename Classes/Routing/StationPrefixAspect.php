<?php

declare(strict_types=1);

namespace nkfire\RescueReports\Routing;

use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Routing\Aspect\PersistedMappableAspectInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class StationPrefixAspect implements PersistedMappableAspectInterface
{
    public function __construct(array $settings = []) {}

    public function resolve(string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_rescuereports_domain_model_station');

        $row = $queryBuilder
            ->select('s.uid')
            ->from('tx_rescuereports_domain_model_station', 's')
            ->join('s', 'tx_rescuereports_domain_model_brigade', 'b', 's.brigade = b.uid')
            ->where(
                $queryBuilder->expr()->eq('b.is_primary', $queryBuilder->createNamedParameter(1, ParameterType::INTEGER)),
                $queryBuilder->expr()->eq('s.deleted', $queryBuilder->createNamedParameter(0, ParameterType::INTEGER)),
                $queryBuilder->expr()->eq('s.hidden', $queryBuilder->createNamedParameter(0, ParameterType::INTEGER)),
                'LOWER(s.prefix) = ' . $queryBuilder->createNamedParameter(mb_strtolower($value))
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        return $row ? (string)$row['uid'] : null;
    }

    public function generate(string $value): ?string
    {
        $uid = (int)$value;

        if ($uid <= 0) {
            return null;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_rescuereports_domain_model_station');

        $row = $queryBuilder
            ->select('s.prefix')
            ->from('tx_rescuereports_domain_model_station', 's')
            ->join('s', 'tx_rescuereports_domain_model_brigade', 'b', 's.brigade = b.uid')
            ->where(
                $queryBuilder->expr()->eq('s.uid', $queryBuilder->createNamedParameter($uid, ParameterType::INTEGER)),
                $queryBuilder->expr()->eq('b.is_primary', $queryBuilder->createNamedParameter(1, ParameterType::INTEGER)),
                $queryBuilder->expr()->eq('s.deleted', $queryBuilder->createNamedParameter(0, ParameterType::INTEGER)),
                $queryBuilder->expr()->eq('s.hidden', $queryBuilder->createNamedParameter(0, ParameterType::INTEGER)),
                $queryBuilder->expr()->neq('s.prefix', $queryBuilder->createNamedParameter(''))
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        return $row ? mb_strtolower($row['prefix']) : null;
    }
}
