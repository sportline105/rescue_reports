<?php
declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(function (): void {

    // Hauptplugin: Eventlist
    $ctypeEventlist = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Eventlist',
        'Rescue Reports: Einsatzübersicht',
        'rescue_reports_eventlist'
    );
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Konfiguration,pi_flexform,',
        $ctypeEventlist,
        'after:subheader'
    );
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
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Konfiguration,pi_flexform,',
        $ctypeStatistics,
        'after:subheader'
    );
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
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Konfiguration,pi_flexform,',
        $ctypeSidebar,
        'after:subheader'
    );
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
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Konfiguration,pi_flexform,',
        $ctypeRss,
        'after:subheader'
    );
    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/rss.xml',
        $ctypeRss
    );

})();
