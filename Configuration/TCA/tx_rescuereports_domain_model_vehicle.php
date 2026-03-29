<?php
return [
    'ctrl' => [
        'title' => 'Fahrzeug',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'versioningWS' => true,
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'iconfile' => 'EXT:rescue_reports/Resources/Public/Icons/tx_rescuereports_domain_model_vehicle.svg',
        'hideTable' => true,
    ],
    'types' => [
        '1' => [
            'showitem' => 'car, name, station, link, image, --div--;Zugriff, hidden',
        ],
    ],
    'columns' => [
        'tstamp' => ['config' => ['type' => 'passthrough']],
        'crdate' => ['config' => ['type' => 'passthrough']],
        'deleted' => ['config' => ['type' => 'passthrough']],
        'hidden' => [
            'exclude' => true,
            'label' => 'Unsichtbar',
            'config' => [
                'type' => 'check',
                'items' => [
                    ['label' => '', 'value' => 1],
                ],
            ],
        ],
        'name' => [
            'exclude' => false,
            'label' => 'Fahrzeugname (wird bei Auswahl des Typs vorbelegt, nur für abweichende Bezeichnungen)',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'link' => [
            'exclude' => true,
            'label' => 'Weiterführender Link',
            'config' => [
                'type' => 'link',
            ],
        ],
        'car' => [
            'exclude' => true,
            'label' => 'Fahrzeugtyp (Fahrzeugtyp auswählen, Fahrzeugname wird nach Speichern automatisch generiert)',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_rescuereports_domain_model_car',
                'foreign_table_where' => 'ORDER BY name ASC',
                'itemsProcFunc' => \nkfire\RescueReports\Utility\CarLabelItemsProcFunc::class . '->addOrganisationToLabel',
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'image' => [
            'exclude' => true,
            'label' => 'Fahrzeugbild',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
                'appearance' => [
                    'createNewRelationLinkTitle' => 'Bild hinzufügen',
                ],
            ],
        ],
        'station' => [
            'exclude' => true,
            'label' => 'Feuerwehr / Station',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_rescuereports_domain_model_station',
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
    ],
];