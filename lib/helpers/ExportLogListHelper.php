<?
namespace DigitalWand\DataRotation\Helpers;
use DigitalWand\AdminHelper\Helper\AdminListHelper;

class ExportLogListHelper extends AdminListHelper
{
	static public $module = 'digitalwand.data_rotation';
	protected static $model = '\DigitalWand\DataRotation\Entities\ExportLogTable';
	static protected $viewName = 'export-log-list';
    static protected $editViewName = 'export-log-detail';
	
	/**
     * Не создаем кнопку добавления элемента (есть в родительском классе)
     */
    protected function addContextMenu()
    {

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