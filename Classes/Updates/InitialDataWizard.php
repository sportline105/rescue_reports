<?php
declare(strict_types=1);

namespace nkfire\RescueReports\Updates;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('rescueReportsInitialData')]
final class InitialDataWizard implements UpgradeWizardInterface
{
    public function __construct(
        private readonly LanguageServiceFactory $languageServiceFactory,
    ) {}

    public function getTitle(): string
    {
        return $this->getLanguageService()->sL(
            'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:wizard.initialData.title'
        );
    }

    public function getDescription(): string
    {
        return $this->getLanguageService()->sL(
            'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:wizard.initialData.description'
        );
    }

    public function executeUpdate(): bool
    {
        if (
            !$this->tableExists('pages')
            || !$this->tableExists('tx_rescuereports_domain_model_organisation')
            || !$this->tableExists('tx_rescuereports_domain_model_car')
        ) {
            return false;
        }

        $pid = $this->getOrCreateSysFolder();
        $organisationMap = $this->createOrganisations($pid);
        $this->createCars($pid, $organisationMap);

        return true;
    }

    public function updateNecessary(): bool
    {
        if (
            !$this->tableExists('tx_rescuereports_domain_model_organisation')
            || !$this->tableExists('tx_rescuereports_domain_model_car')
        ) {
            return false;
        }

        return $this->countRecords('tx_rescuereports_domain_model_organisation') === 0
            || $this->countRecords('tx_rescuereports_domain_model_car') === 0;
    }

    public function getPrerequisites(): array
    {
        return [];
    }

    private function getLanguageService(): LanguageService
    {
        return isset($GLOBALS['BE_USER']) && $GLOBALS['BE_USER'] instanceof BackendUserAuthentication
            ? $this->languageServiceFactory->createFromUserPreferences($GLOBALS['BE_USER'])
            : $this->languageServiceFactory->create('default');
    }

    private function tableExists(string $tableName): bool
    {
        try {
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable($tableName);

            return $connection->createSchemaManager()->tablesExist([$tableName]);
        } catch (\Throwable) {
            return false;
        }
    }

    private function countRecords(string $tableName): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($tableName);

        return (int)$queryBuilder
            ->count('uid')
            ->from($tableName)
            ->executeQuery()
            ->fetchOne();
    }

    private function getOrCreateSysFolder(): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('pages');

        $existingUid = $queryBuilder
            ->select('uid')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq(
                    'doktype',
                    $queryBuilder->createNamedParameter(254)
                ),
                $queryBuilder->expr()->like(
                    'title',
                    $queryBuilder->createNamedParameter('Rescue Reports%')
                )
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();

        if ($existingUid !== false) {
            return (int)$existingUid;
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('pages');

        $connection->insert('pages', [
            'pid' => 0,
            'title' => $this->getLanguageService()->sL(
                'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:wizard.initialData.sysfolderTitle'
            ),
            'doktype' => 254,
            'hidden' => 0,
            'deleted' => 0,
            'tstamp' => time(),
            'crdate' => time(),
            'sorting' => 9999,
            'perms_userid' => 0,
            'perms_groupid' => 0,
            'perms_user' => 31,
            'perms_group' => 27,
            'perms_everybody' => 0,
        ]);

        return (int)$connection->lastInsertId();
    }

    /**
     * @return array<string,int>
     */
    private function createOrganisations(int $pid): array
    {
        $seedData = [
            ['name' => 'Freiwillige Feuerwehr', 'abbreviation' => 'FFW'],
            ['name' => 'Berufsfeuerwehr', 'abbreviation' => 'BF'],
            ['name' => 'Werkfeuerwehr', 'abbreviation' => 'WF'],
            ['name' => 'Deutsches Rotes Kreuz', 'abbreviation' => 'DRK'],
            ['name' => 'Johanniter-Unfall-Hilfe', 'abbreviation' => 'JUH'],
            ['name' => 'Malteser Hilfsdienst', 'abbreviation' => 'MHD'],
            ['name' => 'Technisches Hilfswerk', 'abbreviation' => 'THW'],
            ['name' => 'Arbeiter-Samariter-Bund', 'abbreviation' => 'ASB'],
            ['name' => 'Bergwacht', 'abbreviation' => 'BW'],
            ['name' => 'Deutsche Lebens-Rettungs-Gesellschaft', 'abbreviation' => 'DLRG'],
            ['name' => 'Polizei', 'abbreviation' => 'POL'],
            ['name' => 'Deutsche Bahn', 'abbreviation' => 'DB'],
        ];

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_organisation');

        $map = [];

        foreach ($seedData as $organisation) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_rescuereports_domain_model_organisation');

            $existingUid = $queryBuilder
                ->select('uid')
                ->from('tx_rescuereports_domain_model_organisation')
                ->where(
                    $queryBuilder->expr()->eq(
                        'abbreviation',
                        $queryBuilder->createNamedParameter($organisation['abbreviation'])
                    )
                )
                ->setMaxResults(1)
                ->executeQuery()
                ->fetchOne();

            if ($existingUid !== false) {
                $map[$organisation['abbreviation']] = (int)$existingUid;
                continue;
            }

            $connection->insert('tx_rescuereports_domain_model_organisation', [
                'pid' => $pid,
                'name' => $organisation['name'],
                'abbreviation' => $organisation['abbreviation'],
                'tstamp' => time(),
                'crdate' => time(),
                'hidden' => 0,
                'deleted' => 0,
            ]);

            $map[$organisation['abbreviation']] = (int)$connection->lastInsertId();
        }

        return $map;
    }

    /**
     * @param array<string,int> $organisationMap
     */
    private function createCars(int $pid, array $organisationMap): void
    {
        $seedData = [
            ['name' => 'ELW 1', 'organisation' => 'FFW'],
            ['name' => 'TLF 3000', 'organisation' => 'FFW'],
            ['name' => 'DLA(K) 23/12', 'organisation' => 'FFW'],
            ['name' => 'RW 1', 'organisation' => 'FFW'],
            ['name' => 'GW-L2', 'organisation' => 'FFW'],
            ['name' => 'LF 8/6', 'organisation' => 'FFW'],
            ['name' => 'MTF', 'organisation' => 'FFW'],
            ['name' => 'HLF 20', 'organisation' => 'FFW'],
            ['name' => 'TSF-W', 'organisation' => 'FFW'],
            ['name' => 'LF 10', 'organisation' => 'FFW'],
            ['name' => 'RTW', 'organisation' => 'DRK'],
            ['name' => 'KTW', 'organisation' => 'DRK'],
            ['name' => 'NEF', 'organisation' => 'DRK'],
            ['name' => 'GW-G', 'organisation' => 'FFW'],
            ['name' => 'MLW 5', 'organisation' => 'THW'],
            ['name' => 'GKW', 'organisation' => 'THW'],
            ['name' => 'FustW', 'organisation' => 'POL'],
            ['name' => 'MTW', 'organisation' => 'POL'],
            ['name' => 'TLF 16/25', 'organisation' => 'FFW'],
            ['name' => 'KdoW', 'organisation' => 'FFW'],
            ['name' => 'TLF 24/50', 'organisation' => 'FFW'],
            ['name' => 'HLF 24/20', 'organisation' => 'FFW'],
            ['name' => 'GTLF 12000', 'organisation' => 'FFW'],
            ['name' => 'HLF 20/30', 'organisation' => 'FFW'],
            ['name' => 'Krad', 'organisation' => 'FFW'],
            ['name' => 'VRW', 'organisation' => 'FFW'],
            ['name' => 'LF 20 KatS', 'organisation' => 'FFW'],
            ['name' => 'MZF', 'organisation' => 'FFW'],
            ['name' => 'MLF', 'organisation' => 'FFW'],
            ['name' => 'TLF 16', 'organisation' => 'FFW'],
            ['name' => 'TSF', 'organisation' => 'FFW'],
            ['name' => 'LF 8', 'organisation' => 'FFW'],
            ['name' => 'LF 16/12', 'organisation' => 'FFW'],
            ['name' => 'SW 2000-Tr', 'organisation' => 'FFW'],
            ['name' => 'MZB', 'organisation' => 'FFW'],
            ['name' => 'CBRN-ErkW', 'organisation' => 'FFW'],
            ['name' => 'DLAK 42', 'organisation' => 'FFW'],
            ['name' => 'WLF mit AB-Wasser', 'organisation' => 'FFW'],
            ['name' => 'LF 20', 'organisation' => 'FFW'],
            ['name' => 'WLF mit AB-Gefahrgut', 'organisation' => 'FFW'],
            ['name' => 'HLF 20/16', 'organisation' => 'FFW'],
            ['name' => 'RTB', 'organisation' => 'FFW'],
            ['name' => 'RW 2', 'organisation' => 'FFW'],
            ['name' => 'ELW Sachsen-Anhalt', 'organisation' => 'FFW'],
            ['name' => 'TLF 4000', 'organisation' => 'FFW'],
            ['name' => 'WLF mit AB-Einsatzleitung', 'organisation' => 'FFW'],
            ['name' => 'HLF 20/24', 'organisation' => 'FFW'],
            ['name' => 'GTLF 9000', 'organisation' => 'FFW'],
            ['name' => 'LF 16-TS', 'organisation' => 'FFW'],
            ['name' => 'HLF 10', 'organisation' => 'FFW'],
            ['name' => 'DLK 23/12', 'organisation' => 'FFW'],
        ];

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rescuereports_domain_model_car');

        foreach ($seedData as $car) {
            $organisationUid = $organisationMap[$car['organisation']] ?? null;
            if ($organisationUid === null) {
                continue;
            }

            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_rescuereports_domain_model_car');

            $existingUid = $queryBuilder
                ->select('uid')
                ->from('tx_rescuereports_domain_model_car')
                ->where(
                    $queryBuilder->expr()->eq(
                        'name',
                        $queryBuilder->createNamedParameter($car['name'])
                    ),
                    $queryBuilder->expr()->eq(
                        'organization',
                        $queryBuilder->createNamedParameter($organisationUid)
                    )
                )
                ->setMaxResults(1)
                ->executeQuery()
                ->fetchOne();

            if ($existingUid !== false) {
                continue;
            }

            $connection->insert('tx_rescuereports_domain_model_car', [
                'pid' => $pid,
                'name' => $car['name'],
                'organization' => $organisationUid,
                'tstamp' => time(),
                'crdate' => time(),
                'hidden' => 0,
                'deleted' => 0,
            ]);
        }
    }
}
