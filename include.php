<?
namespace DigitalWand\DataRotation;

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('digitalwand.data_rotation',
    array(
        'DigitalWand\DataRotation\Operators\TablesOperator' => 'lib/operators/TablesOperator.php',
        'DigitalWand\DataRotation\Entities\ExportLogTable' => 'lib/entities/ExportLogTable.php',
        'DigitalWand\DataRotation\Entities\ExportSettingsTable' => 'lib/entities/ExportSettingsTable.php',
        'DigitalWand\DataRotation\Helpers\ExportSettingsListHelper' => 'lib/helpers/ExportSettingsListHelper.php',
        'DigitalWand\DataRotation\Helpers\ExportSettingsEditHelper' => 'lib/helpers/ExportSettingsEditHelper.php',
        'DigitalWand\DataRotation\Helpers\ExportLogListHelper' => 'lib/helpers/ExportLogListHelper.php',
        'DigitalWand\DataRotation\Widgets\ColumnSelectWidget' => 'lib/widgets/ColumnSelectWidget.php',
        'DigitalWand\DataRotation\Widgets\TableSelectWidget' => 'lib/widgets/TableSelectWidget.php',
        'DigitalWand\DataRotation\Widgets\UndumpWidget' => 'lib/widgets/UndumpWidget.php',
    )
);

\DigitalWand\AdminHelper\Loader::includeInterface(__DIR__ . '/lib/admininterface/ExportSettingsAdminInterface.php');
\DigitalWand\AdminHelper\Loader::includeInterface(__DIR__ . '/lib/admininterface/ExportLogAdminInterface.php');