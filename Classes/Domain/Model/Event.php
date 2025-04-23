<?php

namespace In2code\Firefighter\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use In2code\Firefighter\Domain\Model\Car;

class Event extends AbstractEntity
{
    protected string $title = '';
    protected string $description = '';
    protected ?\DateTime $start = null;
    protected ?\DateTime $end = null;

    /** @var ObjectStorage<Car> */
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

    public function getEventVehicleAssignments(): ObjectStorage
    {
        return $this->eventVehicleAssignments;
    }

    public function setEventVehicleAssignments(ObjectStorage $assignments): void
    {
        $this->eventVehicleAssignments = $assignments;
    }
}
