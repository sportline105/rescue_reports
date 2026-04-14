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
        'rescue_reports_eventlist'
    );

    // Register FlexForm (addPiFlexFormValue is still functional in TYPO3 v13+ despite deprecation)
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['rescuereports_eventlist'] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        'rescuereports_eventlist',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml'
    );

    // Statistik-Plugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Statistics',
        'Rescue Reports: Jahresstatistik',
        'rescue_reports_statistics'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['rescuereports_statistics'] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        'rescuereports_statistics',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/statistics.xml'
    );

    // Sidebar-Plugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Sidebar',
        'Rescue Reports: Sidebar',
        'rescue_reports_sidebar'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['rescuereports_sidebar'] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        'rescuereports_sidebar',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/sidebar.xml'
    );

    // RSS-Feed-Plugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Rss',
        'Rescue Reports: RSS-Feed',
        'rescue_reports_rss'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['rescuereports_rss'] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        'rescuereports_rss',
        'FILE:EXT:rescue_reports/Configuration/FlexForms/rss.xml'
    );

})();

