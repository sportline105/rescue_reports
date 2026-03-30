<?php

declare(strict_types=1);

$lll = 'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $lll . 'tx_rescuereports_domain_model_organisation',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => ['disabled' => 'hidden'],
        'iconfile' => 'EXT:rescue_reports/Resources/Public/Icons/tx_rescuereports_domain_model_organisation.svg',
    ],
    'types' => [
        '0' => ['showitem' => 'name, abbreviation, icon'],
    ],
    'columns' => [
        'name' => [
            'label' => $lll . 'tx_rescuereports_domain_model_organisation.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'abbreviation' => [
            'label' => $lll . 'tx_rescuereports_domain_model_organisation.abbreviation',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
            ],
        ],
        'icon' => [
            'exclude' => true,
            'label' => $lll . 'tx_rescuereports_domain_model_organisation.icon',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => 5,
            ],
        ],
    ],
];
