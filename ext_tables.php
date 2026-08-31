<?php
defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Information\Typo3Version;

// Required for TYPO3 12; TYPO3 13+ can instead load the Site Set.
ExtensionManagementUtility::addStaticFile(
    'rescue_reports',
    'Configuration/TypoScript',
    'Rescue Reports'
);

// TYPO3 13+ loads Configuration/page.tsconfig automatically. TYPO3 12 does
// not, so register the same configuration explicitly there.
if ((new Typo3Version())->getMajorVersion() < 13) {
    ExtensionManagementUtility::addPageTSConfig(
        "@import 'EXT:rescue_reports/Configuration/page.tsconfig'"
    );
}
