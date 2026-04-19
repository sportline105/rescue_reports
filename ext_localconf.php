<?php

defined('TYPO3') or die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use nkfire\RescueReports\Controller\EventController;

(static function (): void {
    ExtensionUtility::configurePlugin(
        'RescueReports',
        'Eventlist',
        [
            EventController::class => 'list,show',
        ],
        [
            EventController::class => 'list',
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    ExtensionUtility::configurePlugin(
        'RescueReports',
        'Statistics',
        [
            EventController::class => 'statistics',
        ],
        [
            EventController::class => 'statistics',
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    ExtensionUtility::configurePlugin(
        'RescueReports',
        'Sidebar',
        [
            EventController::class => 'list',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    ExtensionUtility::configurePlugin(
        'RescueReports',
        'Rss',
        [
            EventController::class => 'rss',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    // Register VehicleNameAutoFill hook for DataHandler
    // In TYPO3 13/14, use processDatamap_postProcessFieldArray hook
    // This is called BEFORE the database operation, allowing us to modify fieldArray
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamap_postProcessFieldArray'][] = \nkfire\RescueReports\Hooks\VehicleNameAutoFill::class . '->processDatamap_postProcessFieldArray';
})();