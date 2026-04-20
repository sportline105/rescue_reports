<?php

return [
    'dependencies' => ['core', 'form', 'rte_ckeditor'],
    'tags' => [
        'backend.form',
    ],
    'imports' => [
        '@nkfire/rescue-reports/' => 'EXT:rescue_reports/Resources/Public/JavaScript/Ckeditor/',
    ],
];
