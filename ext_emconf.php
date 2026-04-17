<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'rescue_reports',
    'description' => 'Detailed incident reports for fire departments and emergency services with multiple locations and sub-units. // Detaillierte Einsatzberichte für Feuerwehren und BOS mit mehreren Standorten und Untereinheiten',
    'category' => 'plugin',
    'author' => 'Norbert Külz',
    'author_email' => 'sportline105@googlemail.com',
    'state' => 'beta',
    'clearCacheOnLoad' => 1,
    'version' => '1.1.2',
    'constraints' => [
        'depends' => [
            'typo3' => '13.0.0-14.99.99',
            'php' => '8.2.0-8.99.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
