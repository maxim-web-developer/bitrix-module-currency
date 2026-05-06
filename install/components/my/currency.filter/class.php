<?php
declare(strict_types=1);

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * Компонент фильтрации курсов валют.
 */
class CurrencyFilterComponent extends CBitrixComponent
{
    /**
     * Выполнение компонента
     *
     * @return void
     */
    public function executeComponent(): void
    {
        $this->arResult['GRID_ID'] = (string)($this->arParams['GRID_ID'] ?: 'currency_list_grid');

        // Формирование структуры полей фильтра
        $this->arResult['FILTER_FIELDS'] = [
            [
                'id' => 'CODE',
                'name' => 'Код валюты',
                'type' => 'string',
                'default' => true
            ],
            [
                'id' => 'DATE',
                'name' => 'Дата курса',
                'type' => 'date',
                'default' => true
            ],
            [
                'id' => 'COURSE',
                'name' => 'Курс',
                'type' => 'number',
                'default' => true
            ],
        ];

        $this->includeComponentTemplate();
    }
}
