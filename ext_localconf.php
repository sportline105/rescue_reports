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
})();