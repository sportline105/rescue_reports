return [
    'ctrl' => [
        'title' => 'Fahrzeug-Zuweisung',
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
        'iconfile' => 'EXT:firefighter/Resources/Public/Icons/tx_firefighter_domain_model_eventvehicleassignment.svg',
    ],
    'types' => [
        '1' => ['showitem' => 'event, station, car, --div--;Access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'Sprache',
            'config' => ['type' => 'language'],
        ],
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'Übersetzungsreferenz',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [['', 0]],
                'foreign_table' => 'tx_firefighter_domain_model_eventvehicleassignment',
                'foreign_table_where' => 'AND {#tx_firefighter_domain_model_eventvehicleassignment}.{#pid}=###CURRENT_PID### AND {#tx_firefighter_domain_model_eventvehicleassignment}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l18n_diffsource' => ['config' => ['type' => 'passthrough']],
        'hidden' => ['config' => ['type' => 'check']],
        'starttime' => ['config' => ['type' => 'input', 'renderType' => 'inputDateTime', 'eval' => 'datetime']],
        'endtime' => ['config' => ['type' => 'input', 'renderType' => 'inputDateTime', 'eval' => 'datetime']],

        'event' => [
            'label' => 'Einsatz',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_firefighter_domain_model_event',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'station' => [
            'label' => 'Ortsfeuerwehr',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_firefighter_domain_model_station',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'car' => [
            'label' => 'Fahrzeug',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_firefighter_domain_model_car',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
    ],
];
