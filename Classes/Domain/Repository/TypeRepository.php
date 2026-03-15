<?php
declare(strict_types=1);

namespace In2code\RescueReports\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class TypeRepository extends Repository
{
    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }
}