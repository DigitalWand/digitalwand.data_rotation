<?
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use DigitalWand\DataRotation\Helpers\ExportSettingsEditHelper;
use DigitalWand\DataRotation\Helpers\ExportSettingsListHelper;
use DigitalWand\DataRotation\Helpers\ExportLogListHelper;

if (!Loader::includeModule('digitalwand.admin_helper') || !Loader::includeModule('digitalwand.data_rotation')) return;

Loc::loadMessages(__FILE__);

return array(
    array(
        "parent_menu" => "global_menu_services",
        "section" => "dw_data_rotation",
        "sort" => 130,
        "text" => Loc::getMessage('DATA_ROTATION_MENU_TEXT'),
        "title" => Loc::getMessage('DATA_ROTATION_MENU_TITLE'),
        "icon" => "menu_dw_data_rotation_menu_icon",
        "page_icon" => "menu_dw_data_rotation_page_icon",
        "items_id" => "menu_dw_data_rotation",
        "more_url" => array(
            ExportSettingsEditHelper::getEditPageURL(),
			ExportSettingsListHelper::getListPageURL(),
			ExportLogListHelper::getListPageURL(),
        ),
		'items' => array(
            array(
                "text" => Loc::getMessage("DATA_ROTATION_MENU_SETTINGS_TEXT"),
                "url" => ExportSettingsListHelper::getListPageUrl(),
                "more_url" => ExportSettingsEditHelper::getEditPageURL(),
                "title" => Loc::getMessage("DATA_ROTATION_MENU_SETTINGS_TITLE"),
            ),
            array(
                "text" => Loc::getMessage("DATA_ROTATION_MENU_LOG_TEXT"),
                "url" => ExportLogListHelper::getListPageURL(),
                "more_url" => ExportLogListHelper::getListPageURL(),
                "title" => Loc::getMessage("DATA_ROTATION_MENU_LOG_TITLE"),
            ),
        )
    )
);
?>