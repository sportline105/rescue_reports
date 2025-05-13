<?php

namespace In2code\RescueReports\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use In2code\RescueReports\Domain\Model\Type; // Wichtig für Type-Hinting

class Event extends AbstractEntity
{
    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var \DateTime|null
     */
    protected $start = null;

    /**
     * @var \DateTime|null
     */
    protected $end = null;

    /**
     * @var string
     */
    protected $number = '';

    /**
     * @var string
     */
    protected $location = '';

    /**
     * @var ObjectStorage<Car>
     */
    protected $cars;

    /**
     * @var ObjectStorage<FileReference>
     */
    protected $images;

    /**
     * @var ObjectStorage<Type>
     */
    protected $types;

    public function __construct()
    {
        $this->cars = new ObjectStorage();
        $this->images = new ObjectStorage();
        $this->types = new ObjectStorage();
    }

    // --- Title ---
    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    // --- Description ---
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    // --- Start ---
    public function getStart(): ?\DateTime
    {
        return $this->start;
    }
    public function setStart(?\DateTime $start): void
    {
        $this->start = $start;
    }

    // --- End ---
    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }
    public function setEnd(?\DateTime $end): void
    {
        $this->end = $end;
    }

    // --- Number ---
    public function getNumber(): string
    {
        return $this->number;
    }
    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    // --- Location ---
    public function getLocation(): string
    {
        return $this->location;
    }
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    // --- Cars ---
    /**
     * @return ObjectStorage<Car>
     */
    public function getCars()
    {
        return $this->cars;
    }
    /**
     * @param ObjectStorage<Car> $cars
     */
    public function setCars(ObjectStorage $cars): void
    {
        $this->cars = $cars;
    }
    public function addCar($car): void
    {
        $this->cars->attach($car);
    }
    public function removeCar($car): void
    {
        $this->cars->detach($car);
    }

    // --- Images ---
    /**
     * @return ObjectStorage<FileReference>
     */
    public function getImages()
    {
        return $this->images;
    }
    /**
     * @param ObjectStorage<FileReference> $images
     */
    public function setImages(ObjectStorage $images): void
    {
        $this->images = $images;
    }
    public function addImage(FileReference $image): void
    {
        $this->images->attach($image);
    }
    public function removeImage(FileReference $image): void
    {
        $this->images->detach($image);
    }

    // --- Types (Einsatzart) ---
    /**
     * @return ObjectStorage<Type>
     */
    public function getTypes()
    {
        return $this->types;
    }
    /**
     * @param ObjectStorage<Type> $types
     */
    public function setTypes(ObjectStorage $types): void
    {
        $this->types = $types;
    }
    public function addType(Type $type): void
    {
        $this->types->attach($type);
    }
    public function removeType(Type $type): void
    {
        $this->types->detach($type);
    }
}