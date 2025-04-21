<?php

namespace In2code\rescue_reports\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Event extends AbstractEntity
{
    protected string $title = '';
    protected string $description = '';
    protected ?\DateTime $date = null;

    /** @var ObjectStorage<Car> */
    protected ObjectStorage $cars;

    public function __construct()
    {
        $this->cars = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    // Weitere Getter/Setter folgen...
}
