<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:tx_rescuereports_domain_model_image',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
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
        'iconfile' => 'EXT:rescue_reports/Resources/Public/Icons/tx_rescuereports_domain_model_image.svg',
        'hideTable' => true,
    ],
    'types' => [
        '1' => ['showitem' => 'title, image, --div--;Access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => ['exclude' => true, 'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language', 'config' => ['type' => 'language']],
        'l18n_parent' => ['displayCond' => 'FIELD:sys_language_uid:>:0', 'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent', 'config' => ['type' => 'select', 'items' => [['label' => '', 'value' => 0]], 'foreign_table' => 'tx_rescuereports_domain_model_image', 'foreign_table_where' => 'AND {#tx_rescuereports_domain_model_image}.{#pid}=###CURRENT_PID### AND {#tx_rescuereports_domain_model_image}.{#sys_language_uid} IN (-1,0)', 'default' => 0]],
        'l18n_diffsource' => ['config' => ['type' => 'passthrough']],
        'hidden' => ['label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden', 'config' => ['type' => 'check', 'items' => [['label' => '', 'value' => 1]]]],
        'starttime' => ['exclude' => true, 'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime', 'config' => ['type' => 'datetime', 'default' => 0]],
        'endtime' => ['exclude' => true, 'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime', 'config' => ['type' => 'datetime', 'default' => 0, 'range' => ['upper' => mktime(0, 0, 0, 1, 1, 2038)]]],
        'title' => ['label' => 'Title', 'config' => ['type' => 'input', 'eval' => 'trim', 'required' => true]],
        'image' => [
            'label' => 'Bild',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:label.addFileReference',
                ],
            ],
        ],
    ],
<<<<<<< Updated upstream
],

    ]
];
=======
];
>>>>>>> Stashed changes
