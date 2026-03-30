<?php
declare(strict_types=1);

namespace nkfire\RescueReports\Controller;

use nkfire\RescueReports\Domain\Model\Event;
use nkfire\RescueReports\Domain\Repository\EventRepository;
use nkfire\RescueReports\Domain\Repository\TypeRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class EventController extends ActionController
{
    protected EventRepository $eventRepository;

    protected TypeRepository $typeRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function injectTypeRepository(TypeRepository $typeRepository): void
    {
        $this->typeRepository = $typeRepository;
    }

    public function listAction(?string $searchWord = null): ResponseInterface
    {
        $maxCount = (int)($this->settings['maxCount'] ?? 0);
        $dateFromValue = $this->settings['dateFrom'] ?? null;
        $dateToValue = $this->settings['dateTo'] ?? null;
        $enableSearch = (bool)($this->settings['enableSearch'] ?? false);
        $templateVariant = (string)($this->settings['templateVariant'] ?? 'standard');
        $detailPageUid = $this->normalizeDetailPageUid($this->settings['detailPageUid'] ?? null);

        $allowedTemplateVariants = [
            'standard',
            'sidebar',
            'newdesign',
            'newdesignsidebar',
        ];

        if (!in_array($templateVariant, $allowedTemplateVariants, true)) {
            $templateVariant = 'standard';
        }

        $searchWord = trim((string)$searchWord);

        if ($enableSearch && $searchWord !== '') {
            $events = $this->eventRepository->search($searchWord, $dateFromValue, $dateToValue, $maxCount);
        } else {
            $events = $this->eventRepository->findFiltered($dateFromValue, $dateToValue, $maxCount);
        }

        $this->view->assignMultiple([
            'events' => $events,
            'searchWord' => $searchWord,
            'enableSearch' => $enableSearch,
            'maxCount' => $maxCount,
            'dateFrom' => $this->createDateTimeFromFlexFormValue($dateFromValue),
            'dateTo' => $this->createDateTimeFromFlexFormValue($dateToValue),
            'templateVariant' => $templateVariant,
            'detailPageUid' => $detailPageUid,
            'settings' => $this->settings,
        ]);

        return $this->htmlResponse();
    }

    public function showAction(Event $event): ResponseInterface
    {
        $event = $this->eventRepository->findByUid($event->getUid());
        $groupedVehicleData = $this->groupVehiclesByBrigadeAndStation($event);

        $this->view->assignMultiple([
            'event' => $event,
            'groupedVehicleData' => $groupedVehicleData,
            'detailPageUid' => $this->normalizeDetailPageUid($this->settings['detailPageUid'] ?? null),
            'templateVariant' => (string)($this->settings['templateVariant'] ?? 'standard'),
            'settings' => $this->settings,
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
            $brigadePriority = ($brigade && method_exists($brigade, 'getSorting')) ? $brigade->getSorting() : 9999;
            $stationName = $station->getName();
            $stationSorting = method_exists($station, 'getSorting') ? $station->getSorting() : 9999;
            $stationIsPrimary = method_exists($station, 'isPrimary') ? $station->isPrimary() : false;

            $vehicles = [];
            foreach ($station->getVehicles() as $vehicle) {
                if (in_array($vehicle, $eventVehicles, true)) {
                    $vehicles[] = $vehicle;
                }
            }

            if (!isset($grouped[$brigadePriority])) {
                $grouped[$brigadePriority] = [
                    'name' => $brigadeName,
                    'stations' => [],
                ];
            }

            $grouped[$brigadePriority]['stations'][] = [
                'name' => $stationName,
                'sorting' => $stationSorting,
                'isPrimary' => $stationIsPrimary,
                'vehicles' => $vehicles,
            ];
        }

        ksort($grouped);

        foreach ($grouped as &$group) {
            usort($group['stations'], static function ($a, $b) {
                if ($a['isPrimary'] !== $b['isPrimary']) {
                    return $b['isPrimary'] <=> $a['isPrimary'];
                }
                return $a['sorting'] <=> $b['sorting'];
            });
        }

        return $grouped;
    }

    protected function createDateTimeFromFlexFormValue(mixed $value): ?\DateTimeInterface
    {
        if ($value instanceof \DateTimeInterface) {
            return clone $value;
        }

        if ($value === null || $value === '' || $value === '0') {
            return null;
        }

        if (is_numeric($value)) {
            return (new \DateTime())->setTimestamp((int)$value);
        }

        if (is_string($value) && strtotime($value) !== false) {
            return new \DateTime($value);
        }

        return null;
    }

    protected function normalizeDetailPageUid(mixed $value): ?int
    {
        if (is_array($value)) {
            $value = $value[0] ?? null;
        }

        if (is_string($value) && strpos($value, ',') !== false) {
            $value = explode(',', $value)[0] ?? null;
        }

        if ($value === null || $value === '' || $value === '0') {
            return null;
        }

        return (int)$value;
    }
}