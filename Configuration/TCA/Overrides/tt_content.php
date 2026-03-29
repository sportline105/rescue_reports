<?php
declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(function (): void {
    $typo3Version = (new Typo3Version())->getMajorVersion();

    $pluginSignatureEventlist = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Eventlist',
        'Rescue Reports: Einsätze',
        'rescue_reports_eventlist',
        'plugins',
        'Vollständige Einsatzliste mit Detailansicht'
    );

    $pluginSignatureSidebar = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Sidebar',
        'Rescue Reports: Sidebar',
        'rescue_reports_sidebar',
        'plugins',
        'Kompakte Übersicht für Seitenleisten'
    );

    if ($typo3Version < 14) {
        // v13: klassisches list_type System
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureEventlist] = 'pi_flexform';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignatureEventlist] = 'recursive,select_key,pages';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureSidebar] = 'pi_flexform';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignatureSidebar] = 'recursive,select_key,pages';

        ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignatureEventlist,
            'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml'
        );
        ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignatureSidebar,
            'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml'
        );
    } else {
        // v14: neues CType-System mit explizitem showitem
        $GLOBALS['TCA']['tt_content']['types'][$pluginSignatureEventlist]['showitem'] = '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            pi_flexform,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access
        ';

        $GLOBALS['TCA']['tt_content']['types'][$pluginSignatureSidebar]['showitem'] = '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            pi_flexform,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access
        ';

        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml',
            $pluginSignatureEventlist
        );
        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml',
            $pluginSignatureSidebar
        );
    }

    ExtensionManagementUtility::addStaticFile(
        'rescue_reports',
        'Configuration/TypoScript',
        'Rescue Reports Setup'
    );
})();