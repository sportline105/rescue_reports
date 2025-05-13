<?php
namespace In2code\RescueReports\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use In2code\RescueReports\Domain\Repository\EventRepository;
use In2code\RescueReports\Domain\Model\Event;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
    $stations = $event->getStations()->toArray();

    usort($stations, function($a, $b) {
        return strcmp($a->getBrigade()->getName(), $b->getBrigade()->getName());
    });

    $this->view->assignMultiple([
        'event' => $event,
        'stationsSorted' => $stations
    ]);

    return $this->htmlResponse();
    }
}