<?php
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

use DigitalWand\DataRotation\Widgets\UndumpWidget;

Loc::loadMessages(__FILE__);

AdminBaseHelper::setInterfaceSettings(
    array(
        'FIELDS' => array(
            'ID' => array(
                'WIDGET' => new NumberWidget(),
                'READONLY' => true,
				'TITLE' => Loc::getMessage('EXPORT_LOG_ID'),
				'TAB' => 'MAIN',
            ),
            'RULE_ID' => array(
                'WIDGET' => new NumberWidget(),
                'READONLY' => true,
				'TITLE' => Loc::getMessage('EXPORT_LOG_RULE_ID'),
				'TAB' => 'MAIN',
            ),
            'TABLE_NAME' => array(
                'WIDGET' => new StringWidget(),
                'READONLY' => true,
				'TITLE' => Loc::getMessage('EXPORT_LOG_TABLE_NAME'),
				'TAB' => 'MAIN',
            ),
            'DUMP_FILE_NAME' => array(
                'WIDGET' => new StringWidget(),
                'READONLY' => true,
				'TITLE' => Loc::getMessage('EXPORT_LOG_DUMP_FILE_NAME'),
				'TAB' => 'MAIN',
            ),
            'DIRECTION' => array(
                'WIDGET' => new StringWidget(),
                'READONLY' => true,
				'TITLE' => Loc::getMessage('EXPORT_LOG_DIRECTION'),
				'TAB' => 'MAIN',
            ),
			'UPDATE_AT' => array(
                'WIDGET' => new StringWidget(),
                'READONLY' => true,
				'TITLE' => Loc::getMessage('EXPORT_LOG_UPDATE_AT'),
				'TAB' => 'MAIN',
            ),
			'UNDUMP' => array(
                'WIDGET' => new UndumpWidget(),
                'READONLY' => true,
				'HIDE_WHEN_CREATE' => true,
				'VIRTUAL' => true,
				'EDIT_IN_LIST' => true,
				'TITLE' => Loc::getMessage('EXPORT_LOG_UNDUMP'),
				'TAB' => 'MAIN',
            ),
        ),
		'TABS' => array(
            'MAIN' => Loc::getMessage('EXPORT_LOG_TAB_MAIN'),
        )
    ),
    array(
        '\DigitalWand\DataRotation\Helpers\ExportLogListHelper',
    ),
    "digitalwand.data_rotation"
);