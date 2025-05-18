<?php
return [
    'ctrl' => [
        'title' => 'Fahrzeug',
        'label' => 'name',
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
        'hideTable' => false, // ✅ das verhindert Anzeige im Seitenmodul
    ],
    'types' => [
        '1' => ['showitem' => 'name, car, station, link, image, --div--;Zugriff, hidden'],
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
    'label' => 'Fahrzeugname (wird bei Auswahl des Typs vorbelegt)',
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
            'label' => 'Fahrzeugtyp',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_rescuereports_domain_model_car',
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'image' => [
    'exclude' => true,
    'label' => 'Fahrzeugbild',
    'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
        'image',
        [
            'appearance' => [
                'createNewRelationLinkTitle' => 'Bild hinzufügen',
            ],
            'maxitems' => 1,
        ],
        'jpg,jpeg,png,webp'
    ),
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