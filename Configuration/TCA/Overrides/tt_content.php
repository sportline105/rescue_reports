<?php
declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(function (): void {
    $pluginSignatureEventlist = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Eventlist',
        'Rescue Reports: Einsätze',
        'rescue_reports_eventlist'
    );

    $pluginSignatureSidebar = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Sidebar',
        'Rescue Reports: Sidebar',
        'rescue_reports_sidebar'
    );

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

    ExtensionManagementUtility::addStaticFile(
        'rescue_reports',
        'Configuration/TypoScript',
        'Rescue Reports Setup'
    );

    if ((new Typo3Version())->getMajorVersion() < 12) {
        ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_rescuereports_domain_model_event'
        );
    }
})();