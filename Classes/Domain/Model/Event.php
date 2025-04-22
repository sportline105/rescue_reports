<?php
namespace In2code\Firefighter\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Event extends AbstractEntity
{
    protected string $title = '';
    protected string $description = '';
    protected ?\DateTime $start = null;
    protected ?\DateTime $end = null;

    /** @var ObjectStorage<Car> */
    protected ObjectStorage $cars;

    public function __construct()
    {
        $this->cars = new ObjectStorage();
    }

    // Getter/Setter für title
    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    // Getter/Setter für description
    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    // Getter/Setter für start
    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    public function setStart(?\DateTime $start): void
    {
        $this->start = $start;
    }

    // Getter/Setter für end
    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    public function setEnd(?\DateTime $end): void
    {
        $this->end = $end;
    }

    // Getter/Setter für cars
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
