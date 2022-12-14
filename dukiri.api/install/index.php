<?php
//подключаем основные классы для работы с модулем
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;
use Dukiri\Api\IblockSection;
Loc::loadMessages(__FILE__);

class Dukiri_Api extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();

        $this->MODULE_VERSION = "1.0.0";
        $this->MODULE_VERSION_DATE = "12.12.2022";
        $this->MODULE_ID = 'dukiri.api';
        $this->MODULE_NAME = 'ТЗ';
        $this->MODULE_DESCRIPTION = 'ТЗ';
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = 'dukiri';
        $this->PARTNER_URI = '#';
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
        $this->installEvents();
    }

    public function doUninstall()
    {
        $this->uninstallDB();
        $this->uninstallEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installDB()
    {

    }
    public function uninstallDB()
    {

    }

    public function installEvents() {
        EventManager::getInstance()->registerEventHandler(
            'iblock',
            'OnIBlockPropertyBuildList',
            $this->MODULE_ID,
            'Dukiri\\Api\\IblockSection',
            'GetUserTypeDescription'
        );
    }

    public function uninstallEvents() {
        EventManager::getInstance()->unRegisterEventHandler(
            'iblock',
            'OnIBlockPropertyBuildList',
            $this->MODULE_ID,
            'Dukiri\\Api\\IblockSection',
            'GetUserTypeDescription'
        );

    }
}