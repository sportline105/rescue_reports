<?php
namespace In2code\RescueReports\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use RescueOrganization\RescueReports\Domain\Repository\EventRepository;
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
}