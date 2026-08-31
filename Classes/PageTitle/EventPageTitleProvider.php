<?php

declare(strict_types=1);

namespace nkfire\RescueReports\PageTitle;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

final class EventPageTitleProvider extends AbstractPageTitleProvider
{
    public function __construct(
        private readonly ConnectionPool $connectionPool,
    ) {
    }

    public function getTitle(): string
    {
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        if (!$request instanceof ServerRequestInterface) {
            return $this->title;
        }

        $eventUid = $this->findEventUid($request->getQueryParams());
        if ($eventUid === 0) {
            return $this->title;
        }

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tx_rescuereports_domain_model_event');
        $event = $queryBuilder
            ->select('title', 'start')
            ->from('tx_rescuereports_domain_model_event')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($eventUid, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->eq('hidden', 0),
                $queryBuilder->expr()->eq('deleted', 0)
            )
            ->executeQuery()
            ->fetchAssociative();

        if (!is_array($event) || !is_string($event['title'] ?? null) || $event['title'] === '') {
            return $this->title;
        }

        $title = $event['title'];
        $start = $event['start'] ?? null;
        if (is_string($start) && $start !== '') {
            try {
                $title .= ' – ' . (new \DateTimeImmutable($start))->format('d.m.Y');
            } catch (\Exception) {
                // Keep the incident title if a legacy date value cannot be parsed.
            }
        }

        return $title;
    }

    private function findEventUid(array $parameters): int
    {
        foreach ($parameters as $name => $value) {
            if ($name === 'event' && (is_int($value) || (is_string($value) && ctype_digit($value)))) {
                return (int)$value;
            }

            if (is_array($value)) {
                $eventUid = $this->findEventUid($value);
                if ($eventUid > 0) {
                    return $eventUid;
                }
            }
        }

        return 0;
    }
}
