<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'rescue_reports',
    'description' => 'Extbase-based TYPO3 extension for managing detailed incident reports for fire departments and emergency services with multiple locations and sub-units. // Extbase-basierte TYPO3-Extension zur Verwaltung detaillierter Einsatzberichte für Feuerwehren und BOS mit mehreren Standorten und Untereinheiten.',
    'category' => 'plugin',
    'author' => 'Norbert Külz',
    'author_email' => 'sportline105@googlemail.com',
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'version' => '1.2.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.20-14.99.99',
            'php' => '8.2.0-8.99.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
