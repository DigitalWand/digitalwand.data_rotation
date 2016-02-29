<?
namespace DigitalWand\DataRotation\Helpers;

use Bitrix\Main\Application;
use DigitalWand\AdminHelper\Helper\AdminEditHelper;
use DigitalWand\DataRotation\Operators\TablesOperator;

class ExportSettingsEditHelper extends AdminEditHelper
{
    static public $module = 'digitalwand.data_rotation';
    protected static $model = '\DigitalWand\DataRotation\Entities\ExportSettingsTable';
    static protected $listViewName = 'export-settings-list';
    static protected $viewName = 'export-settings-edit';

    public function __construct(array $fields, array $tabs = array())
    {
        $request = Application::getInstance()->getContext()->getRequest();
        if ($request->isAjaxRequest()) {
            global $APPLICATION;
            $APPLICATION->RestartBuffer();

            $action = $request->get('ajaxAction');
            switch ($action) {
                case 'get_fields':
                    $tableName = $_REQUEST['tableName'];
                    $dbRes = TablesOperator::getTableColumnsList($tableName);
                    $result = array();
                    while ($item = $dbRes->Fetch()) {
                        $result[] = array(
                            'NAME' => $item['Field'],
                            'TYPE' => preg_replace('/\\([0-9]+\\)/', '', $item['Type']),
                        );
                    }

                    header("Content-Type: application/json", true);
                    echo json_encode($result);

                    break;
            }

            exit();
        }

        parent::__construct($fields, $tabs);
    }

}