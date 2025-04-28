<?php

namespace In2code\RescueReports\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use In2code\RescueReports\Domain\Model\EventVehicleAssignment;

class Event extends AbstractEntity
{
    protected string $title = '';
    protected string $description = '';

    /**
     * @var \DateTime|null
     */
    protected ?\DateTime $start = null;

    /**
     * @var \DateTime|null
     */
    protected ?\DateTime $end = null;

    /**
     * @var ObjectStorage<EventVehicleAssignment>
     */
    protected ObjectStorage $eventVehicleAssignments;

    public function __construct()
    {
        $this->eventVehicleAssignments = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    public function setStart(?\DateTime $start): void
    {
        $this->start = $start;
    }

    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    public function setEnd(?\DateTime $end): void
    {
        $this->end = $end;
    }

    /**
     * @return ObjectStorage<EventVehicleAssignment>
     */
    public function getEventVehicleAssignments(): ObjectStorage
    {
        return $this->eventVehicleAssignments;
    }

    /**
     * @param ObjectStorage<EventVehicleAssignment> $assignments
     */
    public function setEventVehicleAssignments(ObjectStorage $assignments): void
    {
        $this->eventVehicleAssignments = $assignments;
    }

    public function addEventVehicleAssignment(EventVehicleAssignment $assignment): void
    {
        $this->eventVehicleAssignments->attach($assignment);
    }

    public function removeEventVehicleAssignment(EventVehicleAssignment $assignment): void
    {
        $this->eventVehicleAssignments->detach($assignment);
    }
}