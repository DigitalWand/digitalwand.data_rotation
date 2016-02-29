<?php

namespace DigitalWand\DataRotation\Entities;

use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;


class ExportLogTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'dw_export_log';
    }

    public static function getMap()
    {
        $fieldsMap = array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true
            ),
            'RULE_ID' => array(
                'data_type' => 'integer'
            ),
            'TABLE_NAME' => array(
                'data_type' => 'string',
            ),
            'DUMP_FILE_NAME' => array(
                'data_type' => 'string',
            ),
            'DIRECTION' => array(
                'data_type' => 'string'
            ),
            'UPDATE_AT' => array(
                'data_type' => 'string'
            ),
        );
        return $fieldsMap;
    }
}