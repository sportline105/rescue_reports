<?php
declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

(function (): void {

    // Hauptplugin
    $pluginSignatureEventlist = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Eventlist',
        'Rescue Reports: Einsatzübersicht',
        'rescue_reports_eventlist',
        'common',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml'
    );

    // Register FlexForm using the returned plugin signature
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureEventlist] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignatureEventlist,
        'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml'
    );

    // Statistik-Plugin
    $pluginSignatureStatistics = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Statistics',
        'Rescue Reports: Jahresstatistik',
        'rescue_reports_statistics',
        'common',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/statistics.xml'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureStatistics] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignatureStatistics,
        'FILE:EXT:rescue_reports/Configuration/FlexForms/statistics.xml'
    );

    // Sidebar-Plugin
    $pluginSignatureSidebar = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Sidebar',
        'Rescue Reports: Sidebar',
        'rescue_reports_sidebar',
        'common',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/sidebar.xml'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureSidebar] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignatureSidebar,
        'FILE:EXT:rescue_reports/Configuration/FlexForms/sidebar.xml'
    );

    // RSS-Feed-Plugin
    $pluginSignatureRss = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Rss',
        'Rescue Reports: RSS-Feed',
        'rescue_reports_rss',
        'common',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/rss.xml'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureRss] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignatureRss,
        'FILE:EXT:rescue_reports/Configuration/FlexForms/rss.xml'
    );

})();

