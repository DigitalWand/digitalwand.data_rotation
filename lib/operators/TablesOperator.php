<?
namespace DigitalWand\DataRotation\Operators;

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;

use DigitalWand\DataRotation\Entities\ExportSettingsTable;
use DigitalWand\DataRotation\Entities\ExportLogTable;

class TablesOperator
{
    private static function getDumpDir()
    {
        return __DIR__ . '/../../dumps/';
    }

    public static function getTablesList()
    {
        global $DB;

        return $DB->Query('SHOW TABLES');
    }

    public static function getTableColumnsList($tableName)
    {
        global $DB;

        return $DB->Query('SHOW COLUMNS FROM `' . $DB->ForSql($tableName) . '`');
    }

    public static function moveToDoubleTable($tableName, $condition)
    {
        global $DB;
        $tableName = $DB->ForSql($tableName);

        $dubleTableName = 'dw_tmp_' . $tableName . '_' . date('Ymd_His');

        $DB->StartTransaction();

        $sqlCreate = 'CREATE TABLE `' . $dubleTableName . '` LIKE `' . $tableName . '`';
        $sqlCopy = 'INSERT INTO `' . $dubleTableName . '` SELECT * FROM `' . $tableName . '` WHERE ' . $condition;
        $sqlDelete = 'DELETE FROM `' . $tableName . '` WHERE ' . $condition;

        //print_r($sqlCreate);
        //print_r($sqlCopy);
        //print_r($sqlDelete);

        if (!$DB->Query($sqlCreate) || !$DB->Query($sqlCopy) || !$DB->Query($sqlDelete)) {
            $DB->Rollback();

            return false;
        }

        $DB->Commit();

        return $dubleTableName;
    }

    private static function moveToOriginalTable($srcTableName, $originalTableName)
    {
        global $DB;

        $DB->StartTransaction();

        $sqlCopy = 'INSERT IGNORE INTO `' . $originalTableName . '` SELECT * FROM `' . $srcTableName . '`';
        $sqlDrop = 'DROP TABLE `' . $srcTableName . '`';

        if (!$DB->Query($sqlCopy) || !$DB->Query($sqlDrop)) {
            $DB->Rollback();

            return false;
        }

        $DB->Commit();

        return true;
    }

    public static function createCondition($columnName, $columnType, $start, $end)
    {
        global $DB;

        $columnName = $DB->ForSql($columnName);
        $start = $DB->ForSql($start);
        $end = $DB->ForSql($end);

        $result = 'TRUE';

        if (!empty($start) && !empty($end)) {
            $result .= ' AND `' . $columnName . '` >= \'' . self::createSimpleCondition($columnType, $start) . '\'';
            $result .= ' AND `' . $columnName . '` <= \'' . self::createSimpleCondition($columnType, $end) . '\'';
        } else {
            if (!empty($start)) {
                $result .= ' AND `' . $columnName . '` >= \'' . self::createSimpleCondition($columnType, $start) . '\'';
            } else {
                if (!empty($end)) {
                    $result .= ' AND `' . $columnName . '` >= \'' . self::createSimpleCondition($columnType,
                            $end) . '\'';
                }
            }
        }

        return $result;
    }

    private static function createSimpleCondition($columnType, $condition)
    {
        global $DB;

        $columnType = strtolower($columnType);
        $columnType = preg_replace('/\\([0-9]+\\)/', '', $columnType);

        $condition = $DB->ForSql($condition);

        $resultValue = $condition;
        switch ($columnType) {
            case 'timestamp':
            case 'date':
            case 'datetime':
                $matches = array();
                if (preg_match('/(now|today)([\\+\\-]?)/', $condition, $matches)) {

                    $condition = preg_replace('/(now|today)([\\+\\-]?)/', '', $condition);
                    $baseValue = time();

                    if ($matches[1] == 'now') {
                        $baseValue = time();
                    } else {
                        if ($matches[1] == 'today') {
                            $baseValue = strtotime(date("Y-m-d 23:59:59"));
                        }
                    }

                    if (!empty($matches[2])) {


                        $value = intval($condition);
                        if (!$value) {
                            $value = strtotime($condition);
                        }
                        if (!$value) {
                            return false;
                        }

                        if ($matches[2] == '+') {
                            $resultValue = $baseValue + $value;
                        } else {
                            if ($matches[2] == '-') {
                                $resultValue = $baseValue - $value;
                            }
                        }
                    } else {
                        $resultValue = $baseValue;
                    }


                    $resultValue = date("Y-m-d H:i:s", $resultValue);
                } else {
                    $value = strtotime($condition);
                    if ($value) {
                        return $condition;
                    }

                    return false;
                }
                break;
            default:
                break;
        }

        return $resultValue;
    }

    public static function execute($id)
    {
        $dbRes = ExportSettingsTable::getById($id);
        $item = false;
        if (!$item = $dbRes->fetch()) {
            return false;
        }

        $tableName = $item['TABLE_NAME'];
        $columnName = $item['COLUMN_NAME'];
        $start = $item['START_VALUE'];
        $finish = $item['FINISH_VALUE'];

        $columnType = false;
        $columns = self::getTableColumnsList($tableName);
        while ($column = $columns->fetch()) {
            if ($columnName == $column['Field']) {
                $columnType = $column['Type'];
            }
        }

        if (!$columnType) {
            return false;
        }

        $sqlCondition = self::createCondition($columnName, $columnType, $start, $finish);

        $newTable = self::moveToDoubleTable($tableName, $sqlCondition);

        if (!$newTable) {
            return false;
        }

        $resultFile = self::dump($newTable);

        if (!$resultFile) {
            return false;
        }


        $update = array(
            "LAST_START_AT" => new DateTime(),
        );

        if (!$item['REPEAT'] || $item['REPEAT'] == 'N') {
            $update['IS_DONE'] = 'Y';
        }
        ExportSettingsTable::update($id, $update);

        ExportLogTable::add(array(
            'RULE_ID' => $id,
            'TABLE_NAME' => $tableName,
            'DUMP_FILE_NAME' => $resultFile,
            'DIRECTION' => 'export',
        ));


        return $newTable;
    }

    public static function runRotation()
    {
        $time = time();
        $date = date('Y-m-d H:i:s', $time);

        global $DB;

        $sql = "SELECT `ID`, `TABLE_NAME` FROM `" . ExportSettingsTable::getTableName() . "` WHERE
			(
				(
					(`IS_DONE` IS NULL OR `IS_DONE` = 'N')
					AND (`REPEAT` IS NULL OR `REPEAT` = 'N')
				)
				OR
				(
					`REPEAT` = 'Y'
					AND
					(
						DATE_ADD(`LAST_START_AT`, INTERVAL `REPEAT_TIME` SECOND) < '" . $date . "'
						OR `LAST_START_AT` = '0000-00-00 00:00:00'
					)
				)
			)
			AND `START_AT` < '" . $date . "'
		";
        //print_r($sql);

        $dbRes = $DB->Query($sql);
        while ($item = $dbRes->fetch()) {
            $result = self::execute($item['ID']);

            echo $result ? $result : 'error: ' . $item['TABLE_NAME'];
            echo PHP_EOL;
        }
    }

    private static function dump($table, $dumpDir = false)
    {
        global $DB, $DBHost, $DBName, $DBLogin, $DBPassword;

        $dumpDir = !$dumpDir ? self::getDumpDir() : $dumpDir;
        if (!file_exists($dumpDir)) {
            mkdir($dumpDir, 0777, true);
        }

        $filename = $dumpDir . $table;

        // делаем дамп в sql файл
        $command = 'mysqldump -u' . $DBLogin . ' -p' . $DBPassword . ' -h' . $DBHost . ' ' . $DBName . ' --skip-lock-tables ' . $table . ' > ' . $filename . '.sql';

        $returnCode = false;
        $output = array();
        exec($command, $output, $returnCode);

        if ($returnCode != 0) {    // произошла ошибка
            exec('rm ' . $filename . '*');

            return false;
        }

        // архивируем
        $command = 'tar -cjf ' . $filename . '.tar.bz2 ' . $filename . '.sql';

        $returnCode = false;
        $output = array();

        exec($command, $output, $returnCode);

        if ($returnCode != 0) {    // произошла ошибка
            exec('rm ' . $filename . '*');

            return false;
        }
        // сносим sql - он нам не нужен
        exec('rm ' . $filename . '.sql');

        $sqlDop = 'DROP TABLE `' . $table . '`';
        $DB->Query($sqlDop);

        return $table . '.tar.bz2';
    }

    public function undump($file, $dumpDir = false)
    {
        global $DB, $DBHost, $DBName, $DBLogin, $DBPassword;

        $dumpDir = !$dumpDir ? self::getDumpDir() : $dumpDir;

        $dubleTableName = str_replace('.tar.bz2', '', $file);

        $matches = false;
        if (!preg_match('/^dw_tmp_(.+)_[0-9]{8}_[0-9]{6}$/', $dubleTableName, $matches)) {
            return false;
        }

        $srcTableName = $matches[1];

        $filename = $dumpDir . $dubleTableName;
        // разархивируем
        $untarDumpDir = str_replace('dumps/', '', $dumpDir);
        $command = 'tar -xf ' . $filename . '.tar.bz2 -C ' . $untarDumpDir;

        $returnCode = false;
        $output = array();


        exec($command, $output, $returnCode);
        if ($returnCode != 0) {    // произошла ошибка
            exec('rm ' . $filename . '.sql');

            return false;
        }

        // делаем дамп в sql файл
        $command = 'mysql -u' . $DBLogin . ' -p' . $DBPassword . ' -h' . $DBHost . ' ' . $DBName . ' < ' . $filename . '.sql';

        $returnCode = false;
        $output = array();

        exec($command, $output, $returnCode);
        if ($returnCode != 0) {    // произошла ошибка
            exec('rm ' . $filename . '.sql');

            return false;
        }

        // сливаем в оригинальную таблицу
        if (!self::moveToOriginalTable($dubleTableName, $srcTableName)) {
            exec('rm ' . $filename . '.sql');

            return false;
        }

        exec('rm ' . $filename . '.sql');

        ExportLogTable::add(array(
            'RULE_ID' => 0,
            'TABLE_NAME' => $srcTableName,
            'DUMP_FILE_NAME' => $file,
            'DIRECTION' => 'import',
        ));

        return true;

        die;
    }
}