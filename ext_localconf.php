<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(
    function ($extKey) {

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1496812651] = array(
            'nodeName' => 'json',
            'priority' => 40,
            'class' => \GeorgRinger\NewsImporticsxml\Hooks\Backend\Element\JsonElement::class
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\GeorgRinger\NewsImporticsxml\Tasks\ImportTask::class] = [
            'extension' => $_EXTKEY,
            'title' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf:task.name',
            'description' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf:task.description',
            'additionalFields' => \GeorgRinger\NewsImporticsxml\Tasks\ImportTaskAdditionalFieldProvider::class
        ];

        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class)->connect(
            \GeorgRinger\News\Domain\Service\NewsImportService::class,
            'postHydrate',
            \GeorgRinger\NewsImporticsxml\Aspect\NewsImportAspect::class,
            'postHydrate'
        );
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News'][] = $_EXTKEY;

        spl_autoload_register(function ($class) {
            if (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($class, 'PicoFeed')) {
                $path = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news_importicsxml') . 'Resources/Private/Contrib/picoFeed/lib/' . str_replace('\\',
                        '/', $class) . '.php';
                require_once($path);
            }
        });

    },
    $_EXTKEY
);
