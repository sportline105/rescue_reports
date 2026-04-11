<?php
// Backend module registration via registerModule() is deprecated in TYPO3 13+
// Backend modules require the new TYPO3 13+ module registration API
// See: https://docs.typo3.org/m/typo3/reference-coreapi/13.0/en-us/ApiOverview/Backend/ModuleAPI/

// MigrationController is available in Classes/Controller/Backend/ but requires
// TYPO3 13+ module registration which uses a different approach

// Eigene Backend-CSS einbinden
$GLOBALS['TYPO3_CONF_VARS']['BE']['customCssFiles'][] = 'EXT:rescue_reports/Resources/Public/Css/backend.css';