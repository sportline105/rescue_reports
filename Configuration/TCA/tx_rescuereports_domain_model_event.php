<?php

return [
    'ctrl' => [
    'title' => 'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:tx_rescuereports_domain_model_event',
    'label' => 'title',
    'tstamp' => 'tstamp',
    'crdate' => 'crdate',
    'cruser_id' => 'cruser_id',
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
    'searchFields' => 'title,description',
    'iconfile' => 'EXT:rescue_reports/Resources/Public/Icons/tx_rescuereports_domain_model_event.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => 'hidden, title, --palette--;;times, number, types, location, description, --div--;Stationen, stations, --div--;Fahrzeuge, cars, --div--;Bilder, images'
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
                'foreign_table' => 'tx_rescuereports_domain_model_event',
                'foreign_table_where' => 'AND {#tx_rescuereports_domain_model_event}.{#pid}=###CURRENT_PID###',
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
                'foreign_table' => 'tx_rescuereports_domain_model_type',
                'MM' => 'tx_rescuereports_event_type_mm',
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
                'itemsProcFunc' => 'In2code\\RescueReports\\Utility\\StationLabelUtility->addGroupedStations',
                'foreign_table' => 'tx_rescuereports_domain_model_station',
                'foreign_table_where' => 'AND 1=0',
                'MM' => 'tx_rescuereports_event_station_mm',
                'size' => 10,
                'maxitems' => 9999,
            ],
        ],

        // Fahrzeugeinsatz (gefiltert)
        'cars' => [
        'label' => 'Eingesetzte Fahrzeuge',
        'description' => 'Eingesetzte Feuerwehren auswählen und Datensatz zwischenspeichern. Die Liste der möglichen Fahrzeuge wird anschließend erzeugt.',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectCheckBox',
            #'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'tx_rescuereports_domain_model_car',
            'MM' => 'tx_rescuereports_event_car_mm', // oder leer, wenn nicht gespeichert werden soll
            'itemsProcFunc' => 'In2code\\RescueReports\\Utility\\CarFilterUtility->filterBySelectedStations',
            'foreign_table_where' => 'AND 1=0',
            'size' => 10,
            'maxitems' => 9999,
            'appearance' => [
            'expandAll' => true,
            ],
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