<?php
return [
    'ctrl' => [
        'title' => 'Event Vehicle Assignment',
        'label' => 'fieldname',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'fieldname',
        'iconfile' => 'EXT:firefighter/Resources/Public/Icons/eventvehicleassignment.svg'
    ],
    'columns' => [
        'fieldname' => [
            'label' => 'Fahrzeugeinsatz',
            'config' => [
                'type' => 'select',
                'itemsProcFunc' => \In2code\Firefighter\Utility\EventVehicleAssignmentUtility::class . '->getAssignmentOptions',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 10,
                'maxitems' => 999,
            ],
        ],
    ],
    'types' => [
        '1' => ['showitem' => 'fieldname']
    ],
];
