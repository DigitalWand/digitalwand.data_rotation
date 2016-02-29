<?php

namespace DigitalWand\DataRotation\AdminInterfaces;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use DigitalWand\AdminHelper\Helper\AdminBaseHelper;
use DigitalWand\AdminHelper\Helper\AdminInterface;
use DigitalWand\AdminHelper\Widget\DateTimeWidget;
use DigitalWand\AdminHelper\Widget\FileWidget;
use DigitalWand\AdminHelper\Widget\CheckboxWidget;
use DigitalWand\AdminHelper\Widget\NumberWidget;
use DigitalWand\AdminHelper\Widget\StringWidget;
use DigitalWand\AdminHelper\Widget\UrlWidget;
use DigitalWand\AdminHelper\Widget\UserWidget;
use DigitalWand\AdminHelper\Widget\VisualEditorWidget;
use DigitalWand\AdminHelper\Widget\ComboBoxWidget;

use DigitalWand\DataRotation\Widgets\ColumnSelectWidget;
use DigitalWand\DataRotation\Widgets\TableSelectWidget;

Loc::loadMessages(__FILE__);

AdminBaseHelper::setInterfaceSettings(
    array(
        'FIELDS' => array(
            'ID' => array(
                'WIDGET' => new NumberWidget(),
                'READONLY' => true,
                'HIDE_WHEN_CREATE' => true,
                'TITLE' => Loc::getMessage('EXPORT_SETTINGS_ID'),
                'TAB' => 'MAIN',
            ),
            'IS_DONE' => array(
                'WIDGET' => new CheckboxWidget(),
                'READONLY' => true,
                'HIDE_WHEN_CREATE' => true,
                'TITLE' => Loc::getMessage('EXPORT_SETTINGS_IS_DONE'),
                'TAB' => 'MAIN',
                'REQUIRED' => false,
            ),
            'TABLE_NAME' => array(
                'WIDGET' => new TableSelectWidget(),
                'ID' => 'tableNameSelect',
                'TITLE' => Loc::getMessage('EXPORT_SETTINGS_TABLE_NAME'),
                'TAB' => 'MAIN',

            ),
            'COLUMN_NAME' => array(
                'WIDGET' => new ColumnSelectWidget(),
                'ID' => 'columnNameSelect',
                'TARGET_ID' => 'tableNameSelect',
                'TITLE' => Loc::getMessage('EXPORT_SETTINGS_COLUMN_NAME'),
                'TAB' => 'MAIN',
            ),
            'START_VALUE' => array(
                'WIDGET' => new StringWidget(),
                'TITLE' => Loc::getMessage('EXPORT_SETTINGS_START_VALUE'),
                'TAB' => 'MAIN',
            ),
            'FINISH_VALUE' => array(
                'WIDGET' => new StringWidget(),
                'TITLE' => Loc::getMessage('EXPORT_SETTINGS_FINISH_VALUE'),
                'TAB' => 'MAIN',
            ),
            'REPEAT' => array(
                'WIDGET' => new CheckboxWidget(),
                'TITLE' => Loc::getMessage('EXPORT_SETTINGS_REPEAT'),
                'TAB' => 'MAIN',
                'REQUIRED' => false,
            ),
            'REPEAT_TIME' => array(
                'WIDGET' => new NumberWidget(),
                'TITLE' => Loc::getMessage('EXPORT_SETTINGS_REPEAT_TIME'),
                'TAB' => 'MAIN',
            ),
            'START_AT' => array(
                'WIDGET' => new DateTimeWidget(),
                'HIDE_WHEN_CREATE' => false,
                'TITLE' => Loc::getMessage('EXPORT_SETTINGS_START_AT'),
                'TAB' => 'MAIN',
            ),
            'LAST_START_AT' => array(
                'WIDGET' => new DateTimeWidget(),
                'READONLY' => true,
                'HIDE_WHEN_CREATE' => true,
                'TITLE' => Loc::getMessage('EXPORT_SETTINGS_LAST_START_AT'),
                'TAB' => 'MAIN',
            ),
        ),
        'TABS' => array(
            'MAIN' => Loc::getMessage('EXPORT_SETTINGS_TAB_MAIN'),
        )
    ),
    array(
        '\DigitalWand\DataRotation\Helpers\ExportSettingsListHelper',
        '\DigitalWand\DataRotation\Helpers\ExportSettingsEditHelper',
    ),
    "digitalwand.data_rotation"
);