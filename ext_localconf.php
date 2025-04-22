<?php

defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'In2code.Firefighter',
    'Eventlist',
    [
        \In2code\Firefighter\Controller\EventController::class => 'list, show',
    ],
    [
        \In2code\Firefighter\Controller\EventController::class => 'list, show',
    ]
);
