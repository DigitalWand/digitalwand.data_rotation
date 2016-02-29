<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (class_exists('digitalwand_data_rotation')) return;

class digitalwand_data_rotation extends CModule
{
    var $MODULE_ID = 'digitalwand.data_rotation';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = 'Y';
    var $MODULE_CSS;
    var $PARTNER_NAME = 'DigitalWand';
    var $PARTNER_URI = 'http://digitalwand.ru';

    function digitalwand_data_rotation()
    {
        include __DIR__ . '/version.php';

        $this->MODULE_VERSION = DATA_ROTATION_VERSION;
        $this->MODULE_VERSION_DATE = DATA_ROTATION_VERSION_DATE;
        $this->MODULE_NAME = Loc::getMessage('DATA_ROTATION_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('DATA_ROTATION_INSTALL_DESCRIPTION');
    }

    function InstallDB()
    {
        global $DB, $APPLICATION;

        $errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . "/local/modules/digitalwand.data_rotation/install/mysql/install.sql");
        if (!empty($errors)) {
            $APPLICATION->ThrowException(implode("", $errors));
            return false;
        }
        return true;
    }

    function UnInstallDB()
    {
        global $DB, $APPLICATION;

        $errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . "/local/modules/digitalwand.data_rotation/install/mysql/uninstall.sql");
        if (!empty($errors)) {
            $APPLICATION->ThrowException(implode("", $errors));
            return false;
        }
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;

        RegisterModule($this->MODULE_ID);
        $this->InstallDB();

        $APPLICATION->IncludeAdminFile(Loc::getMessage('DATA_ROTATION_INSTALL_TITLE'), __DIR__ . '/step.php');
    }

    function DoUninstall()
    {
        global $APPLICATION;

        $eventManager = \Bitrix\Main\EventManager::getInstance();

        UnRegisterModule($this->MODULE_ID);
        $this->UnInstallDB();


        $APPLICATION->IncludeAdminFile(Loc::getMessage('DATA_ROTATION_INSTALL_TITLE'), __DIR__ . '/unstep.php');
    }

}