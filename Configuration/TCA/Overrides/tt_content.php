<?php
declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(function (): void {
    $typo3Version = new Typo3Version();
    $isV14Plus = $typo3Version->getMajorVersion() >= 14;

    // Hauptplugin: Eventlist
    $ctypeEventlist = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Eventlist',
        'Rescue Reports: Einsatzübersicht',
        'rescue_reports_eventlist'
    );

    if ($isV14Plus) {
        // TYPO3 v14+: Use new approach with addToAllTCAtypes
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;Konfiguration,pi_flexform,pages,recursive,',
            $ctypeEventlist,
            'after:subheader'
        );
    } else {
        // TYPO3 v13: Use old approach with addPiFlexFormValue
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$ctypeEventlist] = 'pi_flexform';
    }

    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml',
        $ctypeEventlist
    );

    // Statistik-Plugin
    $ctypeStatistics = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Statistics',
        'Rescue Reports: Jahresstatistik',
        'rescue_reports_statistics'
    );

    if ($isV14Plus) {
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;Konfiguration,pi_flexform,pages,recursive,',
            $ctypeStatistics,
            'after:subheader'
        );
    } else {
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$ctypeStatistics] = 'pi_flexform';
    }

    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/statistics.xml',
        $ctypeStatistics
    );

    // Sidebar-Plugin
    $ctypeSidebar = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Sidebar',
        'Rescue Reports: Sidebar',
        'rescue_reports_sidebar'
    );

    if ($isV14Plus) {
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;Konfiguration,pi_flexform,pages,recursive,',
            $ctypeSidebar,
            'after:subheader'
        );
    } else {
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$ctypeSidebar] = 'pi_flexform';
    }

    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/sidebar.xml',
        $ctypeSidebar
    );

    // RSS-Feed-Plugin
    $ctypeRss = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Rss',
        'Rescue Reports: RSS-Feed',
        'rescue_reports_rss'
    );

    if ($isV14Plus) {
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;Konfiguration,pi_flexform,pages,recursive,',
            $ctypeRss,
            'after:subheader'
        );
    } else {
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$ctypeRss] = 'pi_flexform';
    }

    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/rss.xml',
        $ctypeRss
    );

})();
