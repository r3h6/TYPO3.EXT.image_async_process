<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['imageasyncprocess_configuration'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['imageasyncprocess_configuration'] = array();
}

$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['image_async_process'] = 'R3H6\\ImageAsyncProcess\\Controller\\FileController::readAction';


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['tslib_fe-PostProc'][] = function () {
    /** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
    $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class)->get(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
    $signalSlotDispatcher->connect(\TYPO3\CMS\Core\Resource\ResourceStorage::class, \TYPO3\CMS\Core\Resource\Service\FileProcessingService::SIGNAL_PreFileProcess, 'R3H6\\ImageAsyncProcess\\Slots\\FileProcessingSlot', 'useNullProcessedFile');
};
