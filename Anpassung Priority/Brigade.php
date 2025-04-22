namespace In2code\Firefighter\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Brigade extends AbstractEntity {
    protected string $name = '';
    /**
     * @var ObjectStorage<Station>
     */
    protected ObjectStorage $stations;

    public function __construct() {
        $this->stations = new ObjectStorage();
    }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    /** @return ObjectStorage<Station> */
    public function getStations(): ObjectStorage { return $this->stations; }
    public function setStations(ObjectStorage $stations): void { $this->stations = $stations; }
    public function addStation(Station $station): void { $this->stations->attach($station); }
    public function removeStation(Station $station): void { $this->stations->detach($station); }
}