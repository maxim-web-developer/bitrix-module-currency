<?php
declare(strict_types=1);

namespace My\Currency;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\FloatField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\Type\DateTime;

/**
 * Класс для работы с таблицей курсов валют.
 */
class CurrencyTable extends DataManager
{
    /**
     * Возвращает имя таблицы в БД.
     */
    public static function getTableName(): string
    {
        return 'my_currency_rates';
    }

    /**
     * Возвращает описание структуры сущности.
     */
    public static function getMap(): array
    {
        return [
            // ID: Первичный ключ с автоинкрементом
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => 'ID'
            ]),

            // CODE: Уникальный строковый код (ровно 3 символа)
            new StringField('CODE', [
                'required' => true,
                'unique' => true,
                'title' => 'Код валюты',
                'validation' => fn() => [
                    new LengthValidator(3, 3),
                ]
            ]),

            // DATE: Дата и время (datetime в БД)
            new DatetimeField('DATE', [
                'required' => true,
                'title' => 'Дата и время курса',
                'default_value' => fn() => new DateTime()
            ]),

            // COURSE: Значение курса (float/double в БД)
            new FloatField('COURSE', [
                'required' => true,
                'title' => 'Курс'
            ]),
        ];
    }
}
