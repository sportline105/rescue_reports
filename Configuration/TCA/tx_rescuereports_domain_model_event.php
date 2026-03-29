<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:tx_rescuereports_domain_model_event',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'iconfile' => 'EXT:rescue_reports/Resources/Public/Icons/tx_rescuereports_domain_model_event.png',
    ],
    'types' => [
        '1' => [
            'showitem' => 'hidden, title, --palette--;;times, number, types, location, description, --div--;Eingesetzte Einheiten, stations, --div--;Fahrzeuge, vehicles, --div--;Bilder, images',
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
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_rescuereports_domain_model_event',
                'foreign_table_where' => 'AND {#tx_rescuereports_domain_model_event}.{#pid}=###CURRENT_PID###',
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 1,
            ],
        ],

        // Einsatzzeit
        'start' => [
            'label' => 'Einsatzbeginn',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
                'default' => null,
            ],
        ],
        'end' => [
            'label' => 'Einsatzende',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
                'default' => null,
            ],
        ],

        // Inhaltliche Felder
        'title' => [
            'label' => 'Einsatztitel',
            'config' => [
                'type' => 'input',
                'eval' => 'trim', 'required' => true,
            ],
        ],
        'location' => [
            'label' => 'Einsatzort',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => 'Stadt, Straße // BAB X, Richtung ...',
            ],
        ],
        'number' => [
            'label' => 'Einsatznummer',
            'config' => [
                'type' => 'input',
                'eval' => 'trim', 'required' => true,
                'placeholder' => '26/123',
                'max' => 6,
                'default' => 'ZÖ/',
            ],
        ],
        'description' => [
            'label' => 'Einsatzbericht',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'rows' => 5,
            ],
        ],

        // Typen
        'types' => [
            'label' => 'Einsatzart',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_rescuereports_domain_model_type',
                'foreign_table_where' => '
                    AND tx_rescuereports_domain_model_type.deprecated = 0
                    ORDER BY tx_rescuereports_domain_model_type.title
                ',
                'MM' => 'tx_rescuereports_event_type_mm',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],

        // Stationen
        'stations' => [
            'label' => 'Eingesetzte Einheiten',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'itemsProcFunc' => 'nkfire\\RescueReports\\Utility\\StationLabelUtility->addGroupedStations',
                'foreign_table' => 'tx_rescuereports_domain_model_station',
                'foreign_table_where' => 'AND 1=0',
                'MM' => 'tx_rescuereports_event_station_mm',
                'size' => 10,
                'maxitems' => 9999,
            ],
        ],

        'vehicles' => [
            'exclude' => true,
            'label' => 'Eingesetzte Fahrzeuge',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'itemsProcFunc' => \nkfire\RescueReports\Utility\EventVehicleSelectionUtility::class . '->getAvailableVehicles',
                'size' => 15,
                'maxitems' => 999,
                'multiple' => true,
                            ],
        ],

        // Bilder
        'images' => [
            'label' => 'Bilder',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 10,
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:label.addFileReference',
                ],
            ],
        ],
    ],
];
