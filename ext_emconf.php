<?php

// === ext_emconf.php ===
$EM_CONF[$_EXTKEY] = [
    'title' => 'rescue_reports',
    'description' => 'Darstellung von Feuerwehreinsätzen, Fahrzeugen, Typen und Stationen mit Extbase/Fluid.',
    'category' => 'plugin',
    'author' => 'Dein Name',
    'author_email' => 'dein@example.com',
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-7.6.99',
            'php' => '5.5.0-7.3.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];