<?php
defined('TYPO3_MODE') or die();

if (class_exists(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::class)) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rescuereports_domain_model_event');
}