<?
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use DigitalWand\DataRotation\Operators\TablesOperator;

if(!Main\Loader::includeModule('digitalwand.data_rotation'))
{
	die();
}

$filename = $_REQUEST['fileName'];

$status = TablesOperator::undump($filename);


$result = array(
	'status' => $status ? 'Данные восстановлены' : 'Произошла ошибка',
);

global $APPLICATION;
$APPLICATION->RestartBuffer();
header("Content-Type: application/json", true);
echo json_encode($result);