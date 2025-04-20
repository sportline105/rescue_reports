namespace In2code\Firefighter\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Image extends AbstractEntity {
    protected string $title = '';
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }
}
