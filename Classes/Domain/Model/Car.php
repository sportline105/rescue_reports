<?php

namespace In2code\RescueReports\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

class Car extends AbstractEntity
{
    protected string $name = '';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
    protected ?Organisation $organization = null;

    public function getOrganization(): ?Organisation
    {
        return $this->organization;
    }
    public function setOrganization(?Organisation $organization): void
    {
    $this->organization = $organization;
    }
}
