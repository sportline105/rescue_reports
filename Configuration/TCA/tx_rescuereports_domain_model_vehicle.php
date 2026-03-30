<?php

declare(strict_types=1);

$lll = 'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $lll . 'tx_rescuereports_domain_model_vehicle',
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
            'showitem' => 'car, name, station, link, image, --div--;' . $lll . 'tx_rescuereports_domain_model_vehicle.tab.access, hidden',
        ],
    ],
    'columns' => [
        'tstamp' => ['config' => ['type' => 'passthrough']],
        'crdate' => ['config' => ['type' => 'passthrough']],
        'deleted' => ['config' => ['type' => 'passthrough']],
        'hidden' => [
            'exclude' => true,
            'label' => $lll . 'tx_rescuereports_domain_model_vehicle.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    ['label' => '', 'value' => 1],
                ],
            ],
        ],
        'name' => [
            'exclude' => false,
            'label' => $lll . 'tx_rescuereports_domain_model_vehicle.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'link' => [
            'exclude' => true,
            'label' => $lll . 'tx_rescuereports_domain_model_vehicle.link',
            'config' => [
                'type' => 'link',
            ],
        ],
        'car' => [
            'exclude' => true,
            'label' => $lll . 'tx_rescuereports_domain_model_vehicle.car',
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
            'label' => $lll . 'tx_rescuereports_domain_model_vehicle.image',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
                'appearance' => [
                    'createNewRelationLinkTitle' => $lll . 'tx_rescuereports_domain_model_vehicle.image.addNew',
                ],
            ],
        ],
        'station' => [
            'exclude' => true,
            'label' => $lll . 'tx_rescuereports_domain_model_vehicle.station',
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
