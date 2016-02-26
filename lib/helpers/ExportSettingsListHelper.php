<?
namespace DigitalWand\DataRotation\Helpers;
use DigitalWand\AdminHelper\Helper\AdminListHelper;

class ExportSettingsListHelper extends AdminListHelper
{
	static public $module = 'digitalwand.data_rotation';
	protected static $model = '\DigitalWand\DataRotation\Entities\ExportSettingsTable';
	static protected $viewName = 'export-settings-list';
    static protected $editViewName = 'export-settings-edit';
}