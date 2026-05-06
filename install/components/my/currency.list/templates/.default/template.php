<?php
declare(strict_types=1);

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var CMain $APPLICATION
 */

if (empty($arResult['GRID_ID'])) {
    return;
}

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => $arResult['GRID_ID'],
        'COLUMNS' => [
            ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true],
            ['id' => 'CODE', 'name' => 'Код валюты', 'sort' => 'CODE', 'default' => true],
            ['id' => 'DATE', 'name' => 'Дата и время', 'sort' => 'DATE', 'default' => true],
            ['id' => 'COURSE', 'name' => 'Курс', 'sort' => 'COURSE', 'default' => true],
        ],
        'ROWS' => $arResult['ROWS'] ?? [],
        'NAV_OBJECT' => $arResult['NAV'] ?? null,
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '', ''),
        'PAGE_SIZES' => [
            ['NAME' => '5', 'VALUE' => '5'],
            ['NAME' => '10', 'VALUE' => '10'],
            ['NAME' => '20', 'VALUE' => '20'],
            ['NAME' => '50', 'VALUE' => '50'],
        ],
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_HISTORY' => 'N',
        'SHOW_CHECK_ALL_CHECKBOXES' => false,
        'SHOW_ROW_CHECKBOXES' => false,
        'SHOW_ROW_ACTIONS_MENU' => false,
        'SHOW_GRID_SETTINGS_MENU' => true,
        'SHOW_NAVIGATION_PANEL' => true,
        'SHOW_PAGINATION' => true,
        'SHOW_SELECTED_COUNTER' => false,
        'SHOW_TOTAL_COUNTER' => true,
        'SHOW_PAGESIZE' => true,
        'ALLOW_COLUMNS_SORT' => true,
        'ALLOW_COLUMNS_RESIZE' => true,
        'ALLOW_SORT' => true,
    ]
);
