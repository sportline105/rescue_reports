<?php

/// === Configuration/TCA/Overrides/tt_content.php ===
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'In2code.rescue_reports',
    'Main',
    'rescue_reports: Einsätze'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'rescue_reports',
    'Configuration/TypoScript',
    'rescue_reports Setup'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_rescue_reports_domain_model_event',
    'EXT:rescue_reports/Resources/Private/Language/locallang_csh_tx_rescue_reports_domain_model_event.xlf'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rescue_reports_domain_model_event');