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

    // Register pi_flexform as subtype for v13
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$ctypeEventlist] = 'pi_flexform';

    // Add field to form
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Konfiguration,pi_flexform,',
        $ctypeEventlist,
        'after:subheader'
    );

    // Direct columnsOverrides for v13 - completely bypass ds_pointerField lookup
    if (!isset($GLOBALS['TCA']['tt_content']['types'][$ctypeEventlist])) {
        $GLOBALS['TCA']['tt_content']['types'][$ctypeEventlist] = [];
    }
    $GLOBALS['TCA']['tt_content']['types'][$ctypeEventlist]['columnsOverrides']['pi_flexform'] = [
        'config' => [
            'ds' => [
                'default' => 'FILE:EXT:rescue_reports/Configuration/FlexForms/eventlist.xml',
            ],
        ],
    ];

    // Statistik-Plugin
    $ctypeStatistics = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Statistics',
        'Rescue Reports: Jahresstatistik',
        'rescue_reports_statistics'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$ctypeStatistics] = 'pi_flexform';

    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Konfiguration,pi_flexform,',
        $ctypeStatistics,
        'after:subheader'
    );

    if (!isset($GLOBALS['TCA']['tt_content']['types'][$ctypeStatistics])) {
        $GLOBALS['TCA']['tt_content']['types'][$ctypeStatistics] = [];
    }
    $GLOBALS['TCA']['tt_content']['types'][$ctypeStatistics]['columnsOverrides']['pi_flexform'] = [
        'config' => [
            'ds' => [
                'default' => 'FILE:EXT:rescue_reports/Configuration/FlexForms/statistics.xml',
            ],
        ],
    ];

    // Sidebar-Plugin
    $ctypeSidebar = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Sidebar',
        'Rescue Reports: Sidebar',
        'rescue_reports_sidebar'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$ctypeSidebar] = 'pi_flexform';

    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Konfiguration,pi_flexform,',
        $ctypeSidebar,
        'after:subheader'
    );

    if (!isset($GLOBALS['TCA']['tt_content']['types'][$ctypeSidebar])) {
        $GLOBALS['TCA']['tt_content']['types'][$ctypeSidebar] = [];
    }
    $GLOBALS['TCA']['tt_content']['types'][$ctypeSidebar]['columnsOverrides']['pi_flexform'] = [
        'config' => [
            'ds' => [
                'default' => 'FILE:EXT:rescue_reports/Configuration/FlexForms/sidebar.xml',
            ],
        ],
    ];

    // RSS-Feed-Plugin
    $ctypeRss = ExtensionUtility::registerPlugin(
        'RescueReports',
        'Rss',
        'Rescue Reports: RSS-Feed',
        'rescue_reports_rss'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$ctypeRss] = 'pi_flexform';

    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Konfiguration,pi_flexform,',
        $ctypeRss,
        'after:subheader'
    );

    if (!isset($GLOBALS['TCA']['tt_content']['types'][$ctypeRss])) {
        $GLOBALS['TCA']['tt_content']['types'][$ctypeRss] = [];
    }
    $GLOBALS['TCA']['tt_content']['types'][$ctypeRss]['columnsOverrides']['pi_flexform'] = [
        'config' => [
            'ds' => [
                'default' => 'FILE:EXT:rescue_reports/Configuration/FlexForms/rss.xml',
            ],
        ],
    ];

})();
