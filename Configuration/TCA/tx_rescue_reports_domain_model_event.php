<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:tx_rescue_reports_domain_model_event',
        'label' => 'title',
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
        'searchFields' => 'title,description',
        'iconfile' => 'EXT:rescue_reports/Resources/Public/Icons/tx_rescue_reports_domain_model_event.png'
    ],
    'types' => [
        '1' => ['showitem' => 'title, description, date, --div--;Access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language'
            ]
        ],
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_rescue_reports_domain_model_event',
                'foreign_table_where' => 'AND {#tx_rescue_reports_domain_model_event}.{#pid}=###CURRENT_PID### AND {#tx_rescue_reports_domain_model_event}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ]
        ],
        'l18n_diffsource' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'hidden' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    [
                'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.disable',
                1
            ]
                ],
            ]
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'default' => 0,
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ]
            ]
        ],
        'title' => [
            'label' => 'Title',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,required'
            ]
        ],
        'description' => [
            'label' => 'Description',
            'config' => [
                'type' => 'text',
                'rows' => 5
            ]
        ],
       'date' => [
    'exclude' => 1,
    'label' => 'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:tx_rescue_reports_domain_model_event.date',
    'config' => [
        'type' => 'input',
        'renderType' => 'inputDateTime',
        'eval' => 'datetime,null',
        'dbType' => 'datetime',
        'default' => null,
    ],
],
'cars' => [
    'label' => 'Fahrzeuge',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectMultipleSideBySide',
        'foreign_table' => 'tx_rescue_reports_domain_model_car',
        'MM' => 'tx_rescue_reports_event_car_mm',
        'size' => 5,
        'maxitems' => 9999,
    ]
],
'types' => [
    'label' => 'Einsatzarten',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectMultipleSideBySide',
        'foreign_table' => 'tx_rescue_reports_domain_model_type',
        'MM' => 'tx_rescue_reports_event_type_mm',
        'size' => 5,
        'maxitems' => 9999,
    ]
],
'images' => [
    'label' => 'Bilder',
    'config' => [
        'type' => 'inline',
        'foreign_table' => 'tx_rescue_reports_domain_model_image',
        'foreign_field' => 'event',
        'maxitems' => 99,
        'appearance' => ['collapseAll' => true]
    ]
],
'stations' => [
    'label' => 'Stationen',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectMultipleSideBySide',
        'foreign_table' => 'tx_rescue_reports_domain_model_station',
        'MM' => 'tx_rescue_reports_event_station_mm',
        'size' => 5,
        'maxitems' => 9999,
    ]
],
'brigade' => [
    'label' => 'Feuerwehr',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'foreign_table' => 'tx_rescue_reports_domain_model_brigade',
        'minitems' => 0,
        'maxitems' => 1,
    ]
],
'deployments' => [
   'label' => 'Eingesetzte Einheiten',
   'config' => [
     'type' => 'inline',
     'foreign_table' => 'tx_rescue_reports_domain_model_deployment',
     'foreign_field' => 'event',
     'maxitems' => 9999,
     'appearance' => [
       'collapseAll' => true,
       'levelLinksPosition' => 'top',
       'showSynchronizationLink' => true,
       'showPossibleLocalizationRecords' => true,
       'showAllLocalizationLink' => true
     ]
   ]
 ]
    ]
];