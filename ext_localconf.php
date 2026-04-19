<?php

defined('TYPO3') or die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use nkfire\RescueReports\Controller\EventController;

(static function (): void {
    ExtensionUtility::configurePlugin(
        'RescueReports',
        'Eventlist',
        [
            EventController::class => 'list,show',
        ],
        [
            EventController::class => 'list',
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    ExtensionUtility::configurePlugin(
        'RescueReports',
        'Statistics',
        [
            EventController::class => 'statistics',
        ],
        [
            EventController::class => 'statistics',
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    ExtensionUtility::configurePlugin(
        'RescueReports',
        'Sidebar',
        [
            EventController::class => 'list',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    ExtensionUtility::configurePlugin(
        'RescueReports',
        'Rss',
        [
            EventController::class => 'rss',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
})();

// ---------------------------------------------------------
// DataHandler Hooks (temporär für TYPO3 13/14 Upgrade)
// ---------------------------------------------------------

/**

 * WICHTIG:

 * Diese Hooks stammen aus der v11-Extension und wurden bewusst
 * wieder aktiviert, da die Migration auf PSR-14 Listener aktuell
 * nicht zuverlässig funktioniert (TYPO3 13/14).
 *
 * Aufgaben:
 * 
 * - VehicleNameAutoFill:
 *   Setzt den Fahrzeugnamen automatisch aus dem ausgewählten "car"
 *
 * - DataHandlerHook:
 *   Generiert slug_source für Event-Datensätze (Datum + Typ + Titel),
 *   sodass TYPO3 daraus den finalen Slug erzeugen kann.
 *
 * TODO:

 * Perspektivisch Migration auf PSR-14 Events prüfen,
 * sobald ein stabiler 1:1-Ersatz möglich ist.
 */

// Fahrzeugname automatisch setzen
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
    \nkfire\RescueReports\Hooks\VehicleNameAutoFill::class;

// Slug-Erzeugung für Events
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
    \nkfire\RescueReports\Hooks\DataHandlerHook::class;
