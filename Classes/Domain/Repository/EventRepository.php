<?php
declare(strict_types=1);

namespace nkfire\RescueReports\Domain\Repository;

use DateTime;
use DateTimeInterface;
use nkfire\RescueReports\Domain\Model\Event;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

final class EventRepository extends Repository
{
    public function findOneWithRelationsByUid(int $uid): ?Event
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->equals('uid', $uid));
        $query->setLimit(1);

        $event = $query->execute()->getFirst();

        if ($event instanceof Event) {
            $event->getStations();
            $event->getVehicles();
            return $event;
        }

        return null;
    }

    public function findAllWithRelations(): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->setOrderings($this->getDefaultOrderings());

        return $query->execute();
    }

    public function search(
        string $searchWord = '',
        mixed $dateFrom = null,
        mixed $dateTo = null,
        int $limit = 0
    ): QueryResultInterface {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $constraints = [];

        if (trim($searchWord) !== '') {
            $constraints[] = $query->logicalOr(
                $query->like('title', '%' . $searchWord . '%'),
                $query->like('description', '%' . $searchWord . '%'),
                $query->like('location', '%' . $searchWord . '%'),
                $query->like('types.title', '%' . $searchWord . '%'),
                $query->like('number', '%' . $searchWord . '%')
            );
        }

        $dateConstraints = $this->buildDateConstraints($query, $dateFrom, $dateTo);
        if ($dateConstraints !== []) {
            $constraints = array_merge($constraints, $dateConstraints);
        }

        if ($constraints !== []) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        if ($limit > 0) {
            $query->setLimit($limit);
        }

        $query->setOrderings($this->getDefaultOrderings());

        return $query->execute();
    }

    public function findFiltered(
        mixed $dateFrom = null,
        mixed $dateTo = null,
        int $limit = 0
    ): QueryResultInterface {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $constraints = $this->buildDateConstraints($query, $dateFrom, $dateTo);

        if ($constraints !== []) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        if ($limit > 0) {
            $query->setLimit($limit);
        }

        $query->setOrderings($this->getDefaultOrderings());

        return $query->execute();
    }

    protected function getDefaultOrderings(): array
    {
        return [
            'start' => QueryInterface::ORDER_DESCENDING,
            'number' => QueryInterface::ORDER_DESCENDING,
            'uid' => QueryInterface::ORDER_DESCENDING,
        ];
    }

    protected function buildDateConstraints(
        QueryInterface $query,
        mixed $dateFrom = null,
        mixed $dateTo = null
    ): array {
        $constraints = [];

        $fromDate = $this->convertToDateTime($dateFrom);
        if ($fromDate instanceof DateTimeInterface) {
            $constraints[] = $query->greaterThanOrEqual(
                'start',
                (clone $fromDate)->setTime(0, 0, 0)->format('Y-m-d H:i:s')
            );
        }

        $toDate = $this->convertToDateTime($dateTo);
        if ($toDate instanceof DateTimeInterface) {
            $constraints[] = $query->lessThanOrEqual(
                'start',
                (clone $toDate)->setTime(23, 59, 59)->format('Y-m-d H:i:s')
            );
        }

        return $constraints;
    }

    protected function convertToDateTime(mixed $value): ?DateTime
    {
        if ($value instanceof DateTime) {
            return clone $value;
        }

        if ($value instanceof DateTimeInterface) {
            return new DateTime($value->format('Y-m-d H:i:s'));
        }

        if ($value === null || $value === '' || $value === '0') {
            return null;
        }

        if (is_numeric($value)) {
            return (new DateTime())->setTimestamp((int)$value);
        }

        if (is_string($value) && strtotime($value) !== false) {
            return new DateTime($value);
        }

        return null;
    }
}