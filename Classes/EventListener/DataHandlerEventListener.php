<?php
declare(strict_types=1);

namespace nkfire\RescueReports\EventListener;

use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\DataHandling\Event\BeforeRecordCreatedEvent;
use TYPO3\CMS\Core\DataHandling\Event\BeforeRecordUpdatedEvent;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DataHandlerEventListener
{
    public function beforeRecordCreated(BeforeRecordCreatedEvent $event): void
    {
        if ($event->getTableName() !== 'tx_rescuereports_domain_model_event') {
            return;
        }

        $this->processEventData($event->getRecord(), $event->getUid());
    }

    public function beforeRecordUpdated(BeforeRecordUpdatedEvent $event): void
    {
        if ($event->getTableName() !== 'tx_rescuereports_domain_model_event') {
            return;
        }

        $this->processEventData($event->getRecord(), $event->getUid());
    }

    protected function processEventData(array &$fieldArray, int | string $id): void
    {
        $title = trim((string)($fieldArray['title'] ?? ''));
        if ($title === '') {
            $title = 'einsatz';
        }

        $start = (string)($fieldArray['start'] ?? '');
        $typeTitle = $this->resolveTypeTitle($fieldArray, $id);

        $titleSlug = $this->slugify($title);
        $typeSlug = $this->slugify($typeTitle);

        $slugParts = [];

        if ($start !== '') {
            $timestamp = strtotime($start);
            if ($timestamp !== false) {
                $slugParts[] = date('m', $timestamp);
                $slugParts[] = date('d', $timestamp);
            }
        }

        $lastPart = '';
        if ($typeSlug !== '') {
            $lastPart .= $typeSlug;
        }
        if ($titleSlug !== '') {
            $lastPart .= ($lastPart !== '' ? '/' : '') . $titleSlug;
        }

        if ($lastPart === '') {
            $lastPart = 'einsatz';
        }

        $slugParts[] = $lastPart;

        $fieldArray['slug_source'] = implode('/', $slugParts);
        $fieldArray['slug'] = '';
    }

    protected function resolveTypeTitle(array $fieldArray, int | string $id): string
    {
        $typeUid = 0;

        // Wenn types direkt im aktuellen Speichervorgang mitkommt
        if (!empty($fieldArray['types'])) {
            $typeUid = (int)$fieldArray['types'];
        }

        // Falls nichts mitkommt: aus bestehender MM-Zuordnung lesen
        if ($typeUid <= 0 && (int)$id > 0) {
            $typeUid = $this->getTypeUidFromMm((int)$id);
        }

        if ($typeUid <= 0) {
            return '';
        }

        return $this->getTypeTitleByUid($typeUid);
    }

    protected function getTypeUidFromMm(int $eventUid): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_rescuereports_event_type_mm');

        $row = $queryBuilder
            ->select('uid_foreign')
            ->from('tx_rescuereports_event_type_mm')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid_local',
                    $queryBuilder->createNamedParameter($eventUid, ParameterType::INTEGER)
                )
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        return (int)($row['uid_foreign'] ?? 0);
    }

    protected function getTypeTitleByUid(int $typeUid): string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_rescuereports_domain_model_type');

        $queryBuilder->getRestrictions()->removeAll();

        $row = $queryBuilder
            ->select('title')
            ->from('tx_rescuereports_domain_model_type')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($typeUid, ParameterType::INTEGER)
                ),
                $queryBuilder->expr()->eq(
                    'deleted',
                    $queryBuilder->createNamedParameter(0, ParameterType::INTEGER)
                )
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        return trim((string)($row['title'] ?? ''));
    }

    protected function slugify(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^[:alnum:]]+/u', '-', $text);
        $text = trim((string)$text, '-');

        return $text !== '' ? $text : '';
    }
}
