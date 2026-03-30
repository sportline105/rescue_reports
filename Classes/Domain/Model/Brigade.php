<?php
declare(strict_types=1);

namespace nkfire\RescueReports\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Brigade extends AbstractEntity
{
    protected string $name = '';

    protected int $sorting = 0;

    /**
     * @var ObjectStorage<Station>
     */
    protected ObjectStorage $stations;

    public function __construct()
    {
        $this->stations = new ObjectStorage();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSorting(): int
    {
        return $this->sorting;
    }

    public function setSorting(int $sorting): void
    {
        $this->sorting = $sorting;
    }

    /**
     * @return ObjectStorage<Station>
     */
    public function getStations(): ObjectStorage
    {
        return $this->stations;
    }

    public function setStations(ObjectStorage $stations): void
    {
        $this->stations = $stations;
    }
}