<?php
declare(strict_types=1);

defined('TYPO3') or die();

use nkfire\RescueReports\Controller\EventController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(function (): void {

    // Hauptplugin
    ExtensionUtility::configurePlugin(
        'RescueReports',
        'Eventlist',
        [
            EventController::class => 'list,show',
        ],
        [
            EventController::class => 'list',
        ]
    );

    // Statistik-Plugin
    ExtensionUtility::configurePlugin(
        'RescueReports',
        'Statistics',
        [
            EventController::class => 'statistics',
        ],
        [
            EventController::class => 'statistics',
        ]
    );

    // Sidebar-Plugin
    ExtensionUtility::configurePlugin(
        'RescueReports',
        'Sidebar',
        [
            EventController::class => 'list',
        ],
        [
            EventController::class => '',
        ]
    );

    // RSS-Feed-Plugin
    ExtensionUtility::configurePlugin(
        'RescueReports',
        'Rss',
        [
            EventController::class => 'rss',
        ],
        [
            EventController::class => '',
        ]
    );

    // RTE configuration removed - deprecated in TYPO3 13+

})();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][] = [
    'nodeName' => 'eventVehicleAssignment',
    'priority' => 40,
    'class' => \nkfire\RescueReports\Form\Element\EventVehicleAssignmentElement::class,
];

// Hook registrations have been migrated to event listeners in Services.yaml
