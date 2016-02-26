<?
namespace DigitalWand\DataRotation\Helpers;
use DigitalWand\AdminHelper\Helper\AdminEditHelper;

class ExportSettingsEditHelper extends AdminEditHelper
{
    static public $module = 'digitalwand.data_rotation';
    protected static $model = '\DigitalWand\DataRotation\Entities\ExportSettingsTable';
    static protected $listViewName = 'export-settings-list';
    static protected $viewName = 'export-settings-edit';

}