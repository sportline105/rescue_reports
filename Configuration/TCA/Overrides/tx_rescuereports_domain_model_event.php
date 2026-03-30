<?php

declare(strict_types=1);

defined('TYPO3') or die();

$lll = 'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:';

$tmpColumns = [
    'fahrzeugeinsatz' => [
        'exclude' => 1,
        'label' => $lll . 'tx_rescuereports_domain_model_event.fahrzeugeinsatz',
        'config' => [
            'type' => 'user',
            'renderType' => 'eventVehicleAssignment',
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_rescuereports_domain_model_event',
    $tmpColumns
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_rescuereports_domain_model_event',
    'fahrzeugeinsatz',
    '',
    'after:title'
);
