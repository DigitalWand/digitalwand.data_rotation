<?
namespace DigitalWand\DataRotation\Helpers;
use DigitalWand\AdminHelper\Helper\AdminListHelper;
use Bitrix\Main\Application;
use DigitalWand\DataRotation\Operators\TablesOperator;

class ExportLogListHelper extends AdminListHelper
{
	static public $module = 'digitalwand.data_rotation';
	protected static $model = '\DigitalWand\DataRotation\Entities\ExportLogTable';
	static protected $viewName = 'export-log-list';
    static protected $editViewName = 'export-log-detail';

    public function __construct(array $fields)
    {
        $request = Application::getInstance()->getContext()->getRequest();
        if ($request->isAjaxRequest()) {
            global $APPLICATION;
            $APPLICATION->RestartBuffer();

            $action = $request->get('ajaxAction');
            switch ($action) {
                case 'undump':

                    $filename = $_REQUEST['fileName'];
                    $status = TablesOperator::undump($filename);

                    $result = array(
                        'status' => $status ? 'Данные восстановлены' : 'Произошла ошибка',
                    );

                    header("Content-Type: application/json", true);
                    echo json_encode($result);

                    break;
            }

            exit();
        }

        parent::__construct($fields);
    }

	/**
     * Не создаем кнопку добавления элемента (есть в родительском классе)
     */
    protected function addContextMenu()
    {
        $this->contextMenu = array();
    }

    /**
     * Удаляем кнопку удаления
     * @param $data
     * @return array
     */
    protected function addRowActions($data)
    {
        $actions =  parent::addRowActions($data);
        unset($actions['delete']);
        return $actions;
    }

    /**
     * Тоже уираем кнопку "Удалить" под таблицей
     */
    protected function addGroupActions()
    {

    }
}