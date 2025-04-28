<?php

defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'In2code.RescueReports',
    'Eventlist',
    [
        \In2code\RescueReports\Controller\EventController::class => 'list, show',
    ],
    [
        \In2code\RescueReports\Controller\EventController::class => 'list, show',
    ]
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][] = [
    'nodeName' => 'eventVehicleAssignment',
    'priority' => 40,
    'class' => \In2code\RescueReports\Form\Element\EventVehicleAssignmentElement::class,
];

