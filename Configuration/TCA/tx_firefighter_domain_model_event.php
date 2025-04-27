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
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,description',
        'iconfile' => 'EXT:firefighter/Resources/Public/Icons/tx_firefighter_domain_model_event.png',
    ],

    'types' => [
        '1' => [
            'showitem' => 'hidden, title, --palette--;;times, number, types, location, description, --div--;Stationen, stations, --div--;Fahrzeuge, event_vehicle_assignment, cars, --div--;Bilder, images'
        ],
    ],

    'palettes' => [
        'times' => [
            'showitem' => 'start, end',
            'label' => 'Einsatzzeit',
        ],
    ],

    'columns' => [

        // Systemfelder
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
                'items' => [['', 0]],
                'foreign_table' => 'tx_firefighter_domain_model_event',
                'foreign_table_where' => 'AND {#tx_firefighter_domain_model_event}.{#pid}=###CURRENT_PID###',
                'default' => 0,
            ],
        ],
        'l18n_diffsource' => ['config' => ['type' => 'passthrough']],
        'hidden' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => ['type' => 'check', 'default' => 1],
        ],

        // Einsatzzeit
        'start' => [
            'label' => 'Einsatzbeginn',
            'config' => ['type' => 'input', 'renderType' => 'inputDateTime', 'eval' => 'datetime', 'dbType' => 'datetime', 'default' => null],
        ],
        'end' => [
            'label' => 'Einsatzende',
            'config' => ['type' => 'input', 'renderType' => 'inputDateTime', 'eval' => 'datetime', 'dbType' => 'datetime', 'default' => null],
        ],

        // Inhaltliche Felder
        'title' => [
            'label' => 'Einsatztitel',
            'config' => ['type' => 'input', 'eval' => 'trim,required'],
        ],
        'location' => [
            'label' => 'Einsatzort',
            'config' => ['type' => 'input', 'eval' => 'trim'],
        ],
        'number' => [
            'label' => 'Einsatznummer',
            'config' => ['type' => 'input', 'eval' => 'trim', 'default' => 'ZÖ/'],
        ],
        'description' => [
            'label' => 'Einsatzbericht',
            'config' => ['type' => 'text', 'enableRichtext' => true, 'richtextConfiguration' => 'default', 'rows' => 5],
        ],

        // Typen
        'types' => [
            'label' => 'Einsatzart',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_firefighter_domain_model_type',
                'MM' => 'tx_firefighter_event_type_mm',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],

        // Stationen
        'stations' => [
            'label' => 'Stationen',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'itemsProcFunc' => 'In2code\\Firefighter\\Utility\\StationLabelUtility->addGroupedStations',
                'foreign_table' => 'tx_firefighter_domain_model_station',
                'foreign_table_where' => 'AND 1=0',
                'MM' => 'tx_firefighter_event_station_mm',
                'size' => 10,
                'maxitems' => 9999,
            ],
        ],

        // Fahrzeugeinsatz (gefiltert)
        'event_vehicle_assignment' => [
            'label' => 'Fahrzeugeinsatz',
            'config' => [
                'type' => 'user',
                'renderType' => 'eventVehicleAssignment',
                'maxitems' => 9999,
            ],
        ],

        // Fahrzeuge (alle auswählbar)
        'cars' => [
            'label' => 'Fahrzeuge (manuelle Auswahl)',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_firefighter_domain_model_car',
                'MM' => 'tx_firefighter_event_car_mm',
                'size' => 5,
                'maxitems' => 9999,
            ],
        ],

        // Bilder
        'images' => [
            'label' => 'Bilder',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'images',
                [
                    'maxitems' => 10,
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:label.addFileReference',
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
    ],
];