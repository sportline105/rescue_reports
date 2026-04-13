<?php
declare(strict_types=1);
return [
    'ctrl' => [
        'title' => 'Fahrzeug',
        'label' => 'name',
        ##'label_userFunc' => \nkfire\RescueReports\Utility\VehicleLabelUtility::class . '->getCustomLabel',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'hidden' => 'hidden',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'name',
        'iconfile' => 'EXT:rescue_reports/Resources/Public/Icons/tx_rescuereports_domain_model_vehicle.svg',
        'hideTable' => true, // ✅ das verhindert Anzeige im Seitenmodul
    ],
    'types' => [
        '1' => ['showitem' => 'car, name, station, link, image, --div--;Zugriff, hidden'],
    ],
    'columns' => [
        'tstamp' => ['config' => ['type' => 'passthrough']],
        'crdate' => ['config' => ['type' => 'passthrough']],
        'cruser_id' => ['config' => ['type' => 'passthrough']],
        'deleted' => ['config' => ['type' => 'passthrough']],
        'hidden' => [
            'exclude' => true,
            'label' => 'Unsichtbar',
            'config' => [
                'type' => 'check',
                'items' => [['', 1]],
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
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 50,
                'eval' => 'trim',
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
