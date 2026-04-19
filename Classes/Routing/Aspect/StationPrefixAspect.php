<?php

declare(strict_types=1);

namespace nkfire\RescueReports\Routing\Aspect;

use nkfire\RescueReports\Domain\Model\Station;
use nkfire\RescueReports\Domain\Repository\StationRepository;
use TYPO3\CMS\Core\Routing\Aspect\PersistedPatternMapperInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class StationPrefixAspect implements PersistedPatternMapperInterface
{
    private StationRepository $stationRepository;

    public function __construct(StationRepository $stationRepository)
    {
        $this->stationRepository = $stationRepository;
    }

    /**
     * Resolve incoming URL segment to database value
     * /einsaetze/mh or /einsaetze/MH → resolves to station UID
     */
    public function resolveValue(string $value): ?string
    {
        $station = $this->stationRepository->findByPrefixAndPrimaryBrigade($value);

        if (!$station instanceof Station) {
            return null;
        }

        return (string)$station->getUid();
    }

    /**
     * Generate URL segment from database value
     * station UID → generates /einsaetze/mh (lowercase)
     */
    public function generateValue(string $value): ?string
    {
        $uid = (int)$value;

        if ($uid <= 0) {
            return null;
        }

        $station = $this->stationRepository->findByUid($uid);

        if (!$station instanceof Station) {
            return null;
        }

        $prefix = $station->getPrefix();

        if (empty($prefix)) {
            return null;
        }

        return mb_strtolower($prefix);
    }
}
