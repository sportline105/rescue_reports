<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:firefighter/Resources/Private/Language/locallang_db.xlf:tx_firefighter_domain_model_eventvehicleassignment',
        'label' => 'uid',
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
        'searchFields' => '',
        'iconfile' => 'EXT:firefighter/Resources/Public/Icons/tx_firefighter_domain_model_eventvehicleassignment.svg'
    ],
    'types' => [
        '1' => ['showitem' => 'event, station, cars, --div--;Access, hidden, starttime, endtime'],
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
                'foreign_table' => 'tx_firefighter_domain_model_eventvehicleassignment',
                'foreign_table_where' => 'AND {#tx_firefighter_domain_model_eventvehicleassignment}.{#pid}=###CURRENT_PID### AND {#tx_firefighter_domain_model_eventvehicleassignment}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ]
        ],
        'l18n_diffsource' => ['config' => ['type' => 'passthrough']],
        'hidden' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => ['type' => 'check','items' => [['', 1]]]
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => ['type' => 'input','renderType' => 'inputDateTime','eval' => 'datetime','default' => 0]
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'default' => 0,
                'range' => ['upper' => mktime(0, 0, 0, 1, 1, 2038)]
            ]
        ],
        'event' => [
            'label' => 'Einsatz',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_firefighter_domain_model_event',
                'default' => 0
            ]
        ],
        'station' => [
            'label' => 'Station',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_firefighter_domain_model_station'            ]
        ],
        'cars' => [
            'label' => 'Fahrzeuge',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'itemsProcFunc' => \In2code\Firefighter\Utility\VehicleAssignmentUtility::class . '->filterCarsByStation',
                'foreign_table' => 'tx_firefighter_domain_model_car',
                'MM' => 'tx_firefighter_eventvehicleassignment_car_mm',
                'size' => 5,
                'maxitems' => 9999,
                'foreign_table_where' => 'AND tx_firefighter_domain_model_car.uid IN (SELECT uid_foreign FROM tx_firefighter_station_car_mm WHERE uid_local = ###REC_FIELD_station###)',
            ]
        ],
    ]
];
