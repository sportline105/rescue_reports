namespace In2code\Firefighter\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Brigade extends AbstractEntity {
    protected string $name = '';
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }
}