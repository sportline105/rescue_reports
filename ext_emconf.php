<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'rescue_reports',
    'description' => 'Detaillierte Einsatzberichte für Feuerwehren und BOS',
    'category' => 'plugin',
    'author' => 'Norbert Kuelz',
    'author_email' => 'dein@example.com',
    'state' => 'beta',
    'clearCacheOnLoad' => 1,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.0.0-13.9.99',
            'php' => '7.4.0-8.4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];