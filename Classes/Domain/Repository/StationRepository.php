<?php
declare(strict_types=1);
namespace nkfire\RescueReports\Domain\Repository;

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

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching(
            $query->logicalAnd(
                $query->equals('brigade.isPrimary', true),
                $query->like('LOWER(prefix)', mb_strtolower($prefix))
            )
        );

        $query->setLimit(1);

        $result = $query->execute();
        return $result->count() > 0 ? $result->getFirst() : null;
    }
}