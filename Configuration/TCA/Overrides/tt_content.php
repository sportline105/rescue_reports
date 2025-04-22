<?php

/// === Configuration/TCA/Overrides/tt_content.php ===
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'In2code.Firefighter',
    'Eventlist',
    'Firefighter: Einsätze'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'firefighter',
    'Configuration/TypoScript',
    'Firefighter Setup'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_firefighter_domain_model_event',
    'EXT:firefighter/Resources/Private/Language/locallang_csh_tx_firefighter_domain_model_event.xlf'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_firefighter_domain_model_event');