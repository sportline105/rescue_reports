// === Configuration/Backend/Modules.php ===
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerModule(
    'In2code.Firefighter',
    'tools',
    'migration',
    '',
    [
        \In2code\Firefighter\Controller\Backend\MigrationController::class => 'index,run,resetConfirm,reset',
    ],
    [
        'access' => 'admin',
        'icon' => 'EXT:firefighter/Resources/Public/Icons/module-migration.svg',
        'labels' => 'LLL:EXT:firefighter/Resources/Private/Language/locallang_mod.xlf',
    ]
);