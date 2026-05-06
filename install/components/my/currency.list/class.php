<?php
declare(strict_types=1);

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\Filter\Options as FilterOptions;
use Bitrix\Main\Type\DateTime;
use My\Currency\CurrencyTable;

/**
 * Компонент вывода курсов валют.
 */
class CurrencyListComponent extends CBitrixComponent
{

    public function executeComponent(): void
    {
        try {
            if (!Loader::includeModule('my.currency')) {
                return;
            }

            $gridId = (string)($this->arParams['GRID_ID'] ?: 'currency_list_grid');

            // Постраничная навигация
            $nav = new PageNavigation("nav-currency");
            $nav->allowAllRecords(true)
                ->setPageSize((int)($this->arParams['PAGE_ELEMENT_COUNT'] ?: 10))
                ->initFromUri();

            // Обработка фильтра
            $filterOptions = new FilterOptions($gridId);
            $filterData = $filterOptions->getFilter([]);
            $filter = $this->prepareFilter($filterData);

            // Сортировка и выборка колонок
            $gridOptions = new GridOptions($gridId);
            $sort = $gridOptions->getSorting(['sort' => ['DATE' => 'DESC']]);
            $select = $gridOptions->getVisibleColumns();

            if (empty($select)) {
                $select = ['ID', 'CODE', 'DATE', 'COURSE'];
            }

            // Получение данных
            $dbResult = CurrencyTable::getList([
                'select' => $select,
                'filter' => $filter,
                'order'  => $sort['sort'],
                'offset' => $nav->getOffset(),
                'limit'  => $nav->getLimit(),
                'count_total' => true,
            ]);

            $nav->setRecordCount($dbResult->getCount());

            // Формирование результата
            $this->arResult = [
                'GRID_ID' => $gridId,
                'ROWS'    => [],
                'NAV'     => $nav,
            ];

            while ($row = $dbResult->fetch()) {
                $this->arResult['ROWS'][] = [
                    'id'   => $row['ID'],
                    'data' => $row
                ];
            }

            $this->includeComponentTemplate();

        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }

    /**
     * Подготовка для сбора данных фильтра
     */
    private function prepareFilter(array $filterData): array
    {
        $filter = [];

        if (!empty($filterData['CODE'])) {
            $filter['=CODE'] = (string)$filterData['CODE'];
        }

        if (!empty($filterData['DATE_from'])) {
            $filter['>=DATE'] = new DateTime($filterData['DATE_from']);
        }
        if (!empty($filterData['DATE_to'])) {
            $filter['<=DATE'] = new DateTime($filterData['DATE_to']);
        }

        if (!empty($filterData['COURSE_from'])) {
            $filter['>=COURSE'] = (float)$filterData['COURSE_from'];
        }
        if (!empty($filterData['COURSE_to'])) {
            $filter['<=COURSE'] = (float)$filterData['COURSE_to'];
        }

        return $filter;
    }
}
