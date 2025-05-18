<?php

namespace In2code\RescueReports\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Station extends AbstractEntity
{
    protected string $name = '';

    protected ?Brigade $brigade = null;

    /**
     * @var ObjectStorage<\In2code\RescueReports\Domain\Model\Vehicle>
     */
    protected ObjectStorage $vehicles;

    public function __construct()
    {
        $this->vehicles = new ObjectStorage();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getBrigade(): ?Brigade
    {
        return $this->brigade;
    }

    public function setBrigade(?Brigade $brigade): void
    {
        $this->brigade = $brigade;
    }

    /**
     * @return ObjectStorage<Vehicle>
     */
    public function getVehicles(): ObjectStorage
    {
        return $this->vehicles;
    }

    public function setVehicles(ObjectStorage $vehicles): void
    {
        $this->vehicles = $vehicles;
    }

    public function addVehicle(Vehicle $vehicle): void
    {
        $this->vehicles->attach($vehicle);
    }

    public function removeVehicle(Vehicle $vehicle): void
    {
        $this->vehicles->detach($vehicle);
    }
}
