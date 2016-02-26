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

$tableName = $_REQUEST['tableName'];

$dbRes = TablesOperator::getTableColumnsList($tableName);

$result = array();
while($item = $dbRes->Fetch())
{
	$result[] = array(
		'NAME' => $item['Field'],
		'TYPE' => preg_replace('/\\([0-9]+\\)/', '', $item['Type']),
	);
}

global $APPLICATION;
$APPLICATION->RestartBuffer();
header("Content-Type: application/json", true);
echo json_encode($result);