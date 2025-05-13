<?php
namespace In2code\RescueReports\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use In2code\RescueReports\Domain\Repository\EventRepository;
use In2code\RescueReports\Domain\Model\Event;
use In2code\RescueReports\Domain\Model\Car;
use In2code\RescueReports\Domain\Model\Station;
use Psr\Http\Message\ResponseInterface;

class EventController extends ActionController
{
    protected EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function listAction(): ResponseInterface
    {
        $events = $this->eventRepository->findAll();
        $this->view->assign('events', $events);
        return $this->htmlResponse();
    }

    public function showAction(Event $event): ResponseInterface
    {
        $groupedCarData = $this->groupCarsByBrigadeAndStation($event);
        $this->view->assignMultiple([
            'event' => $event,
            'groupedCarData' => $groupedCarData
        ]);
        return $this->htmlResponse();
    }

    protected function groupCarsByBrigadeAndStation(Event $event): array
    {
        $grouped = [];
        $eventCars = $event->getCars()->toArray();

        foreach ($event->getStations() as $station) {
            $brigade = $station->getBrigade();
            $brigadeName = $brigade ? $brigade->getName() : 'Unbekannt';
            $brigadePriority = $brigade && method_exists($brigade, 'getPriority') ? $brigade->getPriority() : 9999;
            $stationName = $station->getName();
            $stationSorting = method_exists($station, 'getSorting') ? $station->getSorting() : 9999;

            foreach ($station->getCars() as $car) {
                if (in_array($car, $eventCars, true)) {
                    $grouped[$brigadePriority]['name'] = $brigadeName;
                    $grouped[$brigadePriority]['stations'][$stationSorting]['name'] = $stationName;
                    $grouped[$brigadePriority]['stations'][$stationSorting]['cars'][] = $car;
                }
            }
        }

        ksort($grouped);
        foreach ($grouped as &$group) {
            if (isset($group['stations']) && is_array($group['stations'])) {
                ksort($group['stations']);
            }
        }

        return $grouped;
    }
}