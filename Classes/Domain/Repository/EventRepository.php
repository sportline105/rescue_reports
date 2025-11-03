<?php
namespace In2code\RescueReports\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use In2code\RescueReports\Domain\Repository\TypeRepository;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use In2code\RescueReports\Domain\Model\Event;

class EventRepository extends Repository
{
    protected $objectManager;

    public function injectObjectManager(ObjectManager $objectManager): void
    {
        $this->objectManager = $objectManager;
    }

    public function findByUid($uid)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->equals('uid', $uid));
        $query->setLimit(1);

        $event = $query->execute()->getFirst();

        if ($event instanceof \In2code\RescueReports\Domain\Model\Event) {
            $event->getStations();
            $event->getVehicles();
        }

        return $event;
    }

    public function findAllWithRelations()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        return $query->execute();
    }

    public function search(string $searchWord = ''): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        if (trim($searchWord) !== '') {
            $constraints = [
                $query->like('title', '%' . $searchWord . '%'),
                $query->like('description', '%' . $searchWord . '%'),
                $query->like('location', '%' . $searchWord . '%'),
                $query->like('types.title', '%' . $searchWord . '%'),
                $query->like('number', '%' . $searchWord . '%'),
            ];

            $query->matching($query->logicalOr($constraints));
        } else {
            // Kein Suchwort → keine Ergebnisse (sonst zeigt er alle an)
            $query->matching($query->equals('uid', 0));
        }

        return $query->execute();
    }
}