<?php

// === Configuration/TCA/tx_rescuereports_domain_model_car.php ===
return [
    'ctrl' => [
        'title' => 'LLL:EXT:rescue_reports/Resources/Private/Language/locallang_db.xlf:tx_rescuereports_domain_model_car',
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
        'searchFields' => 'name,link',
        'iconfile' => 'EXT:rescue_reports/Resources/Public/Icons/tx_rescuereports_domain_model_car.png'
    ],
    'types' => [
        '1' => ['showitem' => 'name, link, image, --div--;Access, hidden, starttime, endtime'],
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
                'foreign_table' => 'tx_rescuereports_domain_model_car',
                'foreign_table_where' => 'AND {#tx_rescuereports_domain_model_car}.{#pid}=###CURRENT_PID### AND {#tx_rescuereports_domain_model_car}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ]
        ],
        'l18n_diffsource' => ['config' => ['type' => 'passthrough']],
        'hidden' => ['label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden','config' => ['type' => 'check','items' => [['', 1]]]],
        'starttime' => ['exclude' => true,'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime','config' => ['type' => 'input','renderType' => 'inputDateTime','eval' => 'datetime','default' => 0]],
        'endtime' => ['exclude' => true,'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime','config' => ['type' => 'input','renderType' => 'inputDateTime','eval' => 'datetime','default' => 0,'range' => ['upper' => mktime(0, 0, 0, 1, 1, 2038)]]],
        'name' => ['label' => 'Name','config' => ['type' => 'input','eval' => 'trim,required']],
        'link' => ['label' => 'Link','config' => ['type' => 'input','eval' => 'trim']],
'image' => [
    'label' => 'Bild',
    'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
        'image',
        [
            'maxitems' => 1,
            'appearance' => [
                'createNewRelationLinkTitle' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:label.addFileReference'
            ],
        ],
        $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'] // z.B. 'jpg,jpeg,png,gif'
    ),
],
    ]
];
