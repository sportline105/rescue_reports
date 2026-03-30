<?php

declare(strict_types=1);

defined('TYPO3') or die();

$lll = 'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $lll . 'tx_rescuereports_domain_model_event',
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
            'showitem' => 'hidden, title, --palette--;;times, number, types, location, description, --div--;' . $lll . 'tx_rescuereports_domain_model_event.tab.units, stations, --div--;' . $lll . 'tx_rescuereports_domain_model_event.tab.vehicles, vehicles, --div--;' . $lll . 'tx_rescuereports_domain_model_event.tab.images, images',
        ],
    ],

    'palettes' => [
        'times' => [
            'showitem' => 'start, end',
            'label' => $lll . 'tx_rescuereports_domain_model_event.palette.times',
        ],
    ],

    'columns' => [

        // System fields
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

        // Date/time
        'start' => [
            'label' => $lll . 'tx_rescuereports_domain_model_event.start',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
                'default' => null,
            ],
        ],
        'end' => [
            'label' => $lll . 'tx_rescuereports_domain_model_event.end',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
                'default' => null,
            ],
        ],

        // Content fields
        'title' => [
            'label' => $lll . 'tx_rescuereports_domain_model_event.title',
            'config' => [
                'type' => 'input',
                'eval' => 'trim', 'required' => true,
            ],
        ],
        'location' => [
            'label' => $lll . 'tx_rescuereports_domain_model_event.location',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => 'Stadt, Straße // BAB X, Richtung ...',
            ],
        ],
        'number' => [
            'label' => $lll . 'tx_rescuereports_domain_model_event.number',
            'config' => [
                'type' => 'input',
                'eval' => 'trim', 'required' => true,
                'placeholder' => '26/123',
                'max' => 6,
                'default' => '26/',
            ],
        ],
        'description' => [
            'label' => $lll . 'tx_rescuereports_domain_model_event.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'rows' => 5,
            ],
        ],

        // Types
        'types' => [
            'label' => $lll . 'tx_rescuereports_domain_model_event.types',
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

        // Stations
        'stations' => [
            'label' => $lll . 'tx_rescuereports_domain_model_event.stations',
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
            'label' => $lll . 'tx_rescuereports_domain_model_event.vehicles',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'itemsProcFunc' => \nkfire\RescueReports\Utility\EventVehicleSelectionUtility::class . '->getAvailableVehicles',
                'size' => 15,
                'maxitems' => 999,
                'multiple' => true,
            ],
        ],

        // Images
        'images' => [
            'label' => $lll . 'tx_rescuereports_domain_model_event.images',
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
