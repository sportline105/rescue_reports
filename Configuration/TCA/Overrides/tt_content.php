<?php
defined('TYPO3') or die();

call_user_func(function () {
    // Plugin-Registrierung
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'In2code.RescueReports', // Vendor.ExtensionKey
        'Main',                  // Plugin-Name (eindeutiger Identifier)
        'rescue_reports: Einsätze' // Backend-Label
    );

    // FlexForm-Zuordnung (falls benötigt)
    $pluginSignature = 'rescue_reports_main'; // ExtensionKey + PluginName (kleingeschrieben)
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
    
    // TypoScript-Setup hinzufügen
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'rescue_reports',
        'Configuration/TypoScript',
        'rescue_reports Setup'
    );
    
    // Optional: Sprachbeschreibung für Model
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_rescue_reports_domain_model_event',
        'EXT:rescue_reports/Resources/Private/Language/locallang_csh_tx_rescue_reports_domain_model_event.xlf'
    );
    
    // Optional: Tabelle im Backend erlauben
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rescue_reports_domain_model_event');
});
