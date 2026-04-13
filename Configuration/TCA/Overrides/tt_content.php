<?php
declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(function (): void {

    // Hauptplugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Eventlist',
        'Rescue Reports: Einsatzübersicht',
        'rescue_reports_eventlist',
        '',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml'
    );

    // Statistik-Plugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Statistics',
        'Rescue Reports: Jahresstatistik',
        'rescue_reports_statistics',
        '',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/statistics.xml'
    );

    // Sidebar-Plugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Sidebar',
        'Rescue Reports: Sidebar',
        'rescue_reports_sidebar',
        '',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/sidebar.xml'
    );

    // RSS-Feed-Plugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Rss',
        'Rescue Reports: RSS-Feed',
        'rescue_reports_rss',
        '',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/rss.xml'
    );

})();
