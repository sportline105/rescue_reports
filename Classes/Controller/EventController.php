<?php
namespace In2code\RescueReports\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use In2code\RescueReports\Domain\Repository\EventRepository;
use In2code\RescueReports\Domain\Repository\TypeRepository;
use In2code\RescueReports\Domain\Model\Event;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class EventController extends ActionController
{
    protected EventRepository $eventRepository;
    //protected TypeRepository $typeRepository;
    protected \In2code\RescueReports\Domain\Repository\TypeRepository $typeRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function injectTypeRepository(\In2code\RescueReports\Domain\Repository\TypeRepository $typeRepository): void
    {
        $this->typeRepository = $typeRepository;
    }

    public function listAction(string $searchWord = null): ResponseInterface
    {
        $searchWord = (string)($searchWord ?? '');

        if ($searchWord !== '') {
            // Suche
            $events = $this->eventRepository->search($searchWord);
        } else {
            // Standard-Liste
            $events = $this->eventRepository->findAllWithRelations();
        }

        $this->view->assignMultiple([
            'events'     => $events,
            'searchWord' => $searchWord,
        ]);

        return $this->htmlResponse();
    }

    public function showAction(Event $event): ResponseInterface
    {
        $event = $this->eventRepository->findByUid($event->getUid());
        $groupedVehicleData = $this->groupVehiclesByBrigadeAndStation($event);
        $this->view->assignMultiple([
            'event' => $event,
            'groupedVehicleData' => $groupedVehicleData
        ]);
        return $this->htmlResponse();
    }

    protected function groupVehiclesByBrigadeAndStation(Event $event): array
    {
        $grouped = [];
        $eventVehicles = $event->getVehicles()->toArray();

        foreach ($event->getStations() as $station) {
            $brigade = $station->getBrigade();
            $brigadeName = $brigade ? $brigade->getName() : 'Unbekannt';
            $brigadePriority = $brigade && method_exists($brigade, 'getPriority') ? $brigade->getPriority() : 9999;
            $stationName = $station->getName();
            $stationSorting = method_exists($station, 'getSorting') ? $station->getSorting() : 9999;

            $vehicles = [];
            foreach ($station->getVehicles() as $vehicle) {
                if (in_array($vehicle, $eventVehicles, true)) {
                    $vehicles[] = $vehicle;
                }
            }

            $grouped[$brigadePriority]['name'] = $brigadeName;
            $grouped[$brigadePriority]['stations'][] = [
                'name' => $stationName,
                'sorting' => $stationSorting,
                'vehicles' => $vehicles
            ];
        }

        ksort($grouped);
        foreach ($grouped as &$group) {
            if (isset($group['stations']) && is_array($group['stations'])) {
                usort($group['stations'], fn($a, $b) => $a['sorting'] <=> $b['sorting']);
            }
        }

        return $grouped;
    }
}