<?php

namespace In2code\RescueReports\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Station extends AbstractEntity
{
    protected string $name = '';

    /**
     * @var \In2code\RescueReports\Domain\Model\Brigade|null
     */
    protected $brigade = null;

    /**
     * @var ObjectStorage<\In2code\RescueReports\Domain\Model\Car>
     */
    protected $cars;

    public function __construct()
    {
        $this->cars = new ObjectStorage();
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

    public function getCars(): ObjectStorage
    {
        return $this->cars;
    }

    public function setCars(ObjectStorage $cars): void
    {
        $this->cars = $cars;
    }

    public function addCar(Car $car): void
    {
        $this->cars->attach($car);
    }

    public function removeCar(Car $car): void
    {
        $this->cars->detach($car);
    }
}
