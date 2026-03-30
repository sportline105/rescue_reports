<?php

declare(strict_types=1);

$lll = 'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $lll . 'tx_rescuereports_domain_model_brigade',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'name',
        'sortby' => 'sorting',
        'iconfile' => 'EXT:rescue_reports/Resources/Public/Icons/tx_rescuereports_domain_model_brigade.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => 'name, organization, stations, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [['label' => '', 'value' => 0]],
                'foreign_table' => 'tx_rescuereports_domain_model_brigade',
                'foreign_table_where' => 'AND {#tx_rescuereports_domain_model_brigade}.{#pid}=###CURRENT_PID### AND {#tx_rescuereports_domain_model_brigade}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l18n_diffsource' => ['config' => ['type' => 'passthrough']],
        'hidden' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => ['type' => 'check', 'items' => [['label' => '', 'value' => 1]]],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => ['type' => 'datetime', 'default' => 0],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => ['type' => 'datetime', 'default' => 0, 'range' => ['upper' => mktime(0, 0, 0, 1, 1, 2038)]],
        ],
        'name' => [
            'label' => $lll . 'tx_rescuereports_domain_model_brigade.name',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,required',
                'default' => 'Feuerwehr Stadt ...',
            ],
        ],
        'stations' => [
            'label' => $lll . 'tx_rescuereports_domain_model_brigade.stations',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_rescuereports_domain_model_station',
                'foreign_field' => 'brigade',
                'foreign_sortby' => 'sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => true,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'useSortable' => 1,
                ],
            ],
        ],
        'sorting' => [
            'config' => ['type' => 'passthrough'],
        ],
        'organization' => [
            'label' => $lll . 'tx_rescuereports_domain_model_brigade.organization',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_rescuereports_domain_model_organisation',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
    ],
];
