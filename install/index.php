<?php

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Application;
use My\Currency\CurrencyTable;

class my_currency extends CModule
{
    public $MODULE_ID = 'my.currency';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");

        $this->MODULE_VERSION = (string)$arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = (string)$arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = "Курсы валют (my.currency)";
        $this->MODULE_DESCRIPTION = "Модуль для работы с валютами";
        $this->PARTNER_NAME = "Моя Компания";
        $this->PARTNER_URI = "https://example.com";
    }

    /**
     * Метод установки
     */
    public function DoInstall(): void
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallDB();
        $this->InstallFiles();
        $this->InstallData();
    }

    /**
     * Метод удаления
     */
    public function DoUninstall(): void
    {
        $this->UnInstallData();
        $this->UnInstallDB();
        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    /**
     * Установка файлов компонента
     */
    public function InstallFiles(): bool
    {
        // Используем константу __DIR__ для надежности путей
        CopyDirFiles(
            __DIR__ . "/components",
            Application::getDocumentRoot() . "/bitrix/components",
            true,
            true
        );
        return true;
    }

    /**
     * Удаление файлов компонента
     */
    public function UnInstallFiles(): bool
    {
        $root = Application::getDocumentRoot();
        $components = ['currency.list', 'currency.filter'];

        foreach ($components as $component) {
            $path = $root . "/bitrix/components/my/" . $component;
            if (Directory::isDirectoryExists($path)) {
                Directory::deleteDirectory($path);
            }
        }

        $partnerPath = $root . "/bitrix/components/my";
        if (Directory::isDirectoryExists($partnerPath)) {
            $directory = new \Bitrix\Main\IO\Directory($partnerPath);
            if (empty($directory->getChildren())) {
                Directory::deleteDirectory($partnerPath);
            }
        }

        return true;
    }

    /**
     * Создание таблицы
     */
    public function InstallDB(): void
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $connection = Application::getConnection();
            $tableName = CurrencyTable::getTableName();

            if (!$connection->isTableExists($tableName)) {
                CurrencyTable::getEntity()->createDbTable();
            }
        }
    }

    /**
     * Удаление таблицы
     */
    public function UnInstallDB(): void
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $connection = Application::getConnection();
            $tableName = CurrencyTable::getTableName();

            if ($connection->isTableExists($tableName)) {
                $connection->dropTable($tableName);
            }
        }
    }

    /**
     * Заполнение таблицы данными
     */
    public function InstallData(): void
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $testData = [
                ['CODE' => 'USD', 'COURSE' => 92.45],
                ['CODE' => 'EUR', 'COURSE' => 100.12],
                ['CODE' => 'CNY', 'COURSE' => 12.78],
                ['CODE' => 'USQ', 'COURSE' => 92.45],
                ['CODE' => 'EUQ', 'COURSE' => 100.12],
                ['CODE' => 'CNQ', 'COURSE' => 12.78],
                ['CODE' => 'UQD', 'COURSE' => 92.45],
                ['CODE' => 'EQR', 'COURSE' => 100.12],
                ['CODE' => 'CQY', 'COURSE' => 12.78],
                ['CODE' => 'UQQ', 'COURSE' => 92.45],
                ['CODE' => 'EQQ', 'COURSE' => 100.12],
                ['CODE' => 'CQQ', 'COURSE' => 12.78],
            ];

            foreach ($testData as $data) {
                CurrencyTable::add([
                    'CODE'   => $data['CODE'],
                    'COURSE' => (float)$data['COURSE'],
                    'DATE'   => new \Bitrix\Main\Type\DateTime()
                ]);
            }
        }
    }

    /**
     * Очистка данных таблицы
     */
    public function UnInstallData(): void
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $connection = Application::getConnection();
            $tableName = CurrencyTable::getTableName();

            if ($connection->isTableExists($tableName)) {
                $connection->truncateTable($tableName);
            }
        }
    }
}
