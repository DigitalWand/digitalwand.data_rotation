<?php

namespace DigitalWand\DataRotation\Entities;

use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;


class ExportSettingsTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'dw_export_settings';
    }

    public static function getMap()
    {
        $fieldsMap = array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true
            ),
            'IS_DONE' => array(
                'data_type' => 'string'
            ),
            'TABLE_NAME' => array(
                'data_type' => 'string',
            ),
            'COLUMN_NAME' => array(
                'data_type' => 'string',
            ),
            'START_VALUE' => array(
                'data_type' => 'string'
            ),
            'FINISH_VALUE' => array(
                'data_type' => 'string'
            ),
            'REPEAT' => array(
                'data_type' => 'string'
            ),
            'REPEAT_TIME' => array(
                'data_type' => 'string'
            ),
            'START_AT' => array(
                'data_type' => 'string'
            ),
            'LAST_START_AT' => array(
                'data_type' => 'string'
            ),
        );
        return $fieldsMap;
    }
}