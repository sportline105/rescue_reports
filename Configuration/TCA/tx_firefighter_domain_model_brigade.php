<?php

// === Configuration/TCA/tx_firefighter_domain_model_brigade.php ===
return [
    'ctrl' => [
        'title' => 'LLL:EXT:firefighter/Resources/Private/Language/locallang_db.xlf:tx_firefighter_domain_model_brigade',
        'label' => 'name',
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
        'searchFields' => 'name',
        'iconfile' => 'EXT:firefighter/Resources/Public/Icons/tx_firefighter_domain_model_brigade.png'
    ],
    'types' => [
    '1' => ['showitem' => 'name, stations, --div--;Access, hidden, starttime, endtime'],
],
    'columns' => [
        'sys_language_uid' => ['exclude' => true, 'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language','config' => ['type' => 'language']],
        'l18n_parent' => ['displayCond' => 'FIELD:sys_language_uid:>:0','label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent','config' => ['type' => 'select','renderType' => 'selectSingle','items' => [['', 0]],'foreign_table' => 'tx_firefighter_domain_model_brigade','foreign_table_where' => 'AND {#tx_firefighter_domain_model_brigade}.{#pid}=###CURRENT_PID### AND {#tx_firefighter_domain_model_brigade}.{#sys_language_uid} IN (-1,0)','default' => 0]],
        'l18n_diffsource' => ['config' => ['type' => 'passthrough']],
        'hidden' => ['label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden','config' => ['type' => 'check','items' => [['', 1]]]],
        'starttime' => ['exclude' => true,'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime','config' => ['type' => 'input','renderType' => 'inputDateTime','eval' => 'datetime','default' => 0]],
        'endtime' => ['exclude' => true,'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime','config' => ['type' => 'input','renderType' => 'inputDateTime','eval' => 'datetime','default' => 0,'range' => ['upper' => mktime(0, 0, 0, 1, 1, 2038)]]],
        'name' => ['label' => 'Name','config' => ['type' => 'input','eval' => 'trim,required']],
        'stations' => [
            'label' => 'Ortsfeuerwehren',
                'config' => [
                    'type' => 'inline',
                    'foreign_table' => 'tx_firefighter_domain_model_station',
                    'foreign_field' => 'brigade',
                    'foreign_sortby' => 'sorting', // Wichtig für die Sortierung!
                    'maxitems' => 9999,
                    'appearance' => [
                        'collapseAll' => true,
                        'levelLinksPosition' => 'top',
                        'showSynchronizationLink' => true,
                        'showPossibleLocalizationRecords' => true,
                        'showAllLocalizationLink' => true,
                        'useSortable' => 1, // Aktiviert Drag & Drop im Backend
                                            // weitere Appearance-Optionen nach Bedarf
                    ]
            ]
        ]
    ]
];