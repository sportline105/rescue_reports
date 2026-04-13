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
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

    // Statistik-Plugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Statistics',
        'Rescue Reports: Jahresstatistik',
        'rescue_reports_statistics'
    );

    $pluginSignatureStats = 'rescuereports_statistics';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureStats] = 'pi_flexform';

    // Sidebar-Plugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Sidebar',
        'Rescue Reports: Sidebar',
        'rescue_reports_sidebar'
    );

    $pluginSignatureSidebar = 'rescuereports_sidebar';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureSidebar] = 'pi_flexform';

    // RSS-Feed-Plugin
    ExtensionUtility::registerPlugin(
        'RescueReports',
        'Rss',
        'Rescue Reports: RSS-Feed',
        'rescue_reports_rss'
    );

    $pluginSignatureRss = 'rescuereports_rss';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureRss] = 'pi_flexform';

})();