<?php
declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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

    // Register FlexForm (addPiFlexFormValue is still functional in TYPO3 v13+ despite deprecation)
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['rescue_reports_eventlist'] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        'rescue_reports_eventlist',
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

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['rescue_reports_statistics'] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        'rescue_reports_statistics',
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

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['rescue_reports_sidebar'] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        'rescue_reports_sidebar',
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

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['rescue_reports_rss'] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        'rescue_reports_rss',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/rss.xml'
    );

})();

