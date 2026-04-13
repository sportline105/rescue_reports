<?php
declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(function (): void {

    // Hauptplugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Eventlist',
        'Rescue Reports: Einsatzübersicht',
        'rescue_reports_eventlist'
    );

    $pluginSignature = 'rescuereports_eventlist';
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        'pi_flexform',
        $pluginSignature,
        'after:subheader'
    );
    ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignature,
        'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml'
    );

    // Statistik-Plugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Statistics',
        'Rescue Reports: Jahresstatistik',
        'rescue_reports_statistics'
    );

    $pluginSignatureStats = 'rescuereports_statistics';
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        'pi_flexform',
        $pluginSignatureStats,
        'after:subheader'
    );
    ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignatureStats,
        'FILE:EXT:rescue_reports/Configuration/FlexForms/statistics.xml'
    );

    // Sidebar-Plugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Sidebar',
        'Rescue Reports: Sidebar',
        'rescue_reports_sidebar'
    );

    $pluginSignatureSidebar = 'rescuereports_sidebar';
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        'pi_flexform',
        $pluginSignatureSidebar,
        'after:subheader'
    );
    ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignatureSidebar,
        'FILE:EXT:rescue_reports/Configuration/FlexForms/sidebar.xml'
    );

    // RSS-Feed-Plugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Rss',
        'Rescue Reports: RSS-Feed',
        'rescue_reports_rss'
    );

    $pluginSignatureRss = 'rescuereports_rss';
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        'pi_flexform',
        $pluginSignatureRss,
        'after:subheader'
    );
    ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignatureRss,
        'FILE:EXT:rescue_reports/Configuration/FlexForms/rss.xml'
    );

    // addStaticFile and CSH (Context-Sensitive Help) are deprecated in TYPO3 13+
    // TypoScript configuration is now handled through Site Sets or extension configuration
})();