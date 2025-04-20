namespace In2code\Firefighter\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Station extends AbstractEntity {
    protected string $name = '';
    protected ?Brigade $brigade = null;

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getBrigade(): ?Brigade { return $this->brigade; }
    public function setBrigade(?Brigade $brigade): void { $this->brigade = $brigade; }
}