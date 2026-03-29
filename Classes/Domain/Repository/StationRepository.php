<?php
declare(strict_types=1);

namespace nkfire\RescueReports\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class StationRepository extends Repository
{
    public function findAllGroupedByBrigade(): array
    {
        $query = $this->createQuery();

        $query->getQuerySettings()->setRespectStoragePage(true);

        $query->setOrderings([
            'brigade.priority' => QueryInterface::ORDER_ASCENDING,
            'brigade.name' => QueryInterface::ORDER_ASCENDING,
            'name' => QueryInterface::ORDER_ASCENDING
        ]);

        return $query->execute()->toArray();
    }
}