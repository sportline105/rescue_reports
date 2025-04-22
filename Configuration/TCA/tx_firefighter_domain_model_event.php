<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:firefighter/Resources/Private/Language/locallang_db.xlf:tx_firefighter_domain_model_event',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'delete' => 'deleted',
        'ignorePageTypeRestriction' => true,
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,description',
        'iconfile' => 'EXT:firefighter/Resources/Public/Icons/tx_firefighter_domain_model_event.png'
    ],
    'types' => [
        '1' => [
            'showitem' => 'hidden, title, --palette--;;times, description, cars, types, stations, brigade, --div--;Bilder, images'
        ],
    ],
    'palettes' => [
        'times' => [
            'showitem' => 'start, end',
            'label' => 'Einsatzzeit',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language']
        ],
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [['', 0]],
                'foreign_table' => 'tx_firefighter_domain_model_event',
                'foreign_table_where' => 'AND {#tx_firefighter_domain_model_event}.{#pid}=###CURRENT_PID### AND {#tx_firefighter_domain_model_event}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ]
        ],
        'l18n_diffsource' => ['config' => ['type' => 'passthrough']],
        'hidden' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.disable', 1]]
            ]
        ],
        'start' => [
            'label' => 'Einsatzbeginn',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,null',
                'dbType' => 'datetime',
                'default' => null,
            ],
        ],
        'end' => [
            'label' => 'Einsatzende',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,null',
                'dbType' => 'datetime',
                'default' => null,
            ],
        ],
        'title' => [
            'label' => 'Title',
            'config' => ['type' => 'input', 'eval' => 'trim,required']
        ],
        'description' => [
            'label' => 'Description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'rows' => 5
            ]
        ],
        'cars' => [
            'label' => 'Fahrzeuge',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_firefighter_domain_model_car',
                'MM' => 'tx_firefighter_event_car_mm',
                'size' => 5,
                'maxitems' => 9999,
            ]
        ],
        'types' => [
            'label' => 'Einsatzarten',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_firefighter_domain_model_type',
                'MM' => 'tx_firefighter_event_type_mm',
                'minitems' => 0,
                'maxitems' => 1,
            ]
        ],
        'images' => [
            'label' => 'Bild',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'maxitems' => 10,
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:label.addFileReference'
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'stations' => [
            'label' => 'Stationen',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'itemsProcFunc' => 'In2code\\Firefighter\\Utility\\StationLabelUtility->addGroupedStations',
                'items' => [],
                'MM' => 'tx_firefighter_event_station_mm',
                'maxitems' => 9999,
            ]
        ],
        'brigade' => [
            'label' => 'Feuerwehr',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_firefighter_domain_model_brigade',
                'minitems' => 0,
                'maxitems' => 1,
            ]
        ],
    ]
];