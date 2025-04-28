<?php

/// === Configuration/TCA/Overrides/tt_content.php ===
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'In2code.RescueReports',
    'Eventlist',
    'Rescue Reports: Einsätze'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'rescue_reports',
    'Configuration/TypoScript',
    'Rescue Reports Setup'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_rescuereports_domain_model_event',
    'EXT:rescue_reports/Resources/Private/Language/locallang_csh_tx_rescuereports_domain_model_event.xlf'
);

// In TYPO3 11 notwendig:
$versionInformation = new \TYPO3\CMS\Core\Information\Typo3Version();
if ($versionInformation->getMajorVersion() < 12) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rescuereports_domain_model_event');
}