namespace In2code\rescue_reports\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Car extends AbstractEntity {
    protected string $name = '';
    protected string $link = '';
    protected ?FileReference $image = null;

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getLink(): string { return $this->link; }
    public function setLink(string $link): void { $this->link = $link; }

    public function getImage(): ?FileReference { return $this->image; }
    public function setImage(?FileReference $image): void { $this->image = $image; }
}