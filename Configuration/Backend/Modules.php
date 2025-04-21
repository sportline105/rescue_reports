use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerModule(
    'In2code.rescue_reports',
    'tools',
    'migration',
    '',
    [
        \In2code\rescue_reports\Controller\Backend\MigrationController::class => 'index,run,resetConfirm,reset',
    ],
    [
        'access' => 'admin',
        'icon' => 'EXT:rescue_reports/Resources/Public/Icons/module-migration.svg',
        'labels' => 'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_mod.xlf',
    ]
);