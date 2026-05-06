<?php
declare(strict_types=1);

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arParams
 * @var CMain $APPLICATION
 */

$gridId = (string)($arResult['GRID_ID'] ?? '');

if ($gridId === '') {
    return;
}

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.filter',
    '',
    [
        'FILTER_ID' => $gridId,
        'GRID_ID' => $gridId,
        'FILTER' => $arResult['FILTER_FIELDS'] ?? [],
        'ENABLE_LIVE_SEARCH' => true,
        'ENABLE_LABEL' => true,
        'THEME' => \Bitrix\Main\UI\Filter\Theme::LIGHT,
    ]
);
