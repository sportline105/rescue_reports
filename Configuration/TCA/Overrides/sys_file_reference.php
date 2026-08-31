<?php

declare(strict_types=1);

defined('TYPO3') or die();

$GLOBALS['TCA']['sys_file_reference']['columns']['tx_rescuereports_credit'] = [
    'exclude' => 0,
    'label' => 'Foto: Urheber/in',
    'config' => [
        'type' => 'input',
        'size' => 30,
        'max' => 255,
        'eval' => 'trim',
    ],
];

$imageOverlayPalette = &$GLOBALS['TCA']['sys_file_reference']['palettes']['imageoverlayPalette']['showitem'];
if (is_string($imageOverlayPalette) && strpos($imageOverlayPalette, 'tx_rescuereports_credit') === false) {
    $imageOverlayPalette .= ', tx_rescuereports_credit';
}
