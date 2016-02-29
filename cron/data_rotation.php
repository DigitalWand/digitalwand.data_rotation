<?
$_SERVER["DOCUMENT_ROOT"] = str_replace('/local/modules/digitalwand.data_rotation/cron', '', __DIR__);
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main;
use Bitrix\Main\Text\Converter;
use Bitrix\Main\Localization\Loc;

use DigitalWand\DataRotation\Operators\TablesOperator;

if (!Main\Loader::includeModule('digitalwand.data_rotation')) {
    die();
}

TablesOperator::runRotation();
