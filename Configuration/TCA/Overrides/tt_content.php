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
        // TYPO3 v14+: ds_pointerField removed, use columnsOverrides via '*' wildcard
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;Konfiguration,pi_flexform,pages,recursive,',
            $ctypeEventlist,
            'after:subheader'
        );
        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml',
            $ctypeEventlist
        );
    } else {
        // TYPO3 v13: ds_pointerField='list_type,CType' → key = ',CType' (empty list_type)
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            'pi_flexform',
            $ctypeEventlist,
            'after:subheader'
        );
        ExtensionManagementUtility::addPiFlexFormValue(
            '',
            'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml',
            $ctypeEventlist
        );
    }

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
        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:rescue_reports/Configuration/FlexForms/statistics.xml',
            $ctypeStatistics
        );
    } else {
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            'pi_flexform',
            $ctypeStatistics,
            'after:subheader'
        );
        ExtensionManagementUtility::addPiFlexFormValue(
            '',
            'FILE:EXT:rescue_reports/Configuration/FlexForms/statistics.xml',
            $ctypeStatistics
        );
    }

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
        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:rescue_reports/Configuration/FlexForms/sidebar.xml',
            $ctypeSidebar
        );
    } else {
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            'pi_flexform',
            $ctypeSidebar,
            'after:subheader'
        );
        ExtensionManagementUtility::addPiFlexFormValue(
            '',
            'FILE:EXT:rescue_reports/Configuration/FlexForms/sidebar.xml',
            $ctypeSidebar
        );
    }

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
        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:rescue_reports/Configuration/FlexForms/rss.xml',
            $ctypeRss
        );
    } else {
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            'pi_flexform',
            $ctypeRss,
            'after:subheader'
        );
        ExtensionManagementUtility::addPiFlexFormValue(
            '',
            'FILE:EXT:rescue_reports/Configuration/FlexForms/rss.xml',
            $ctypeRss
        );
    }

})();
