<?php

namespace DigitalWand\DataRotation\Widgets;

use DigitalWand\AdminHelper\Widget\ComboBoxWidget;
use DigitalWand\DataRotation\Operators\TablesOperator;

/**
 * Class ComboBoxWidget Выпадающий список
 * Доступные опции:
 * <ul>
 * <li> STYLE - inline-стили</li>
 * <li> VARIANTS - массив с вариантами занчений или функция для их получения</li>
 * <li> DEFAULT_VARIANT - ID варианта по-умолчанию</li>
 * </ul>
 */
class TableSelectWidget extends ComboBoxWidget
{
    static protected $defaults = array(
        'EDIT_IN_LIST' => true
    );

    /**
     * Генерирует HTML для редактирования поля
     * @see AdminEditHelper::showField();
     * @param bool $forFilter
     * @return mixed
     */
    protected function genEditHTML($forFilter = false)
    {
        $style = $this->getSettings('STYLE');

        $name = $forFilter ? $this->getFilterInputName() : $this->getEditInputName();
        $result = "<select name='" . $name . "' style='" . $style . "' " . ($this->getSettings('ID') ? "id='" . $this->getSettings('ID') . "'" : "") . ">";
        $variants = $this->getVariants();
        $default = $this->getValue();
        if (is_null($default)) {
            $default = $this->getSettings('DEFAULT_VARIANT');
        }

        foreach ($variants as $id => $name) {
            $result .= "<option value='" . $id . "' " . ($id == $default ? "selected" : "") . ">" . $name . "</option>";
        }

        $result .= "</select>";

        return $result;
    }

    /**
     * Возвращает массив в формате
     * <code>
     * array(
     *      '123' => array('ID' => 123, 'TITLE' => 'ololo'),
     *      '456' => array('ID' => 456, 'TITLE' => 'blablabla'),
     *      '789' => array('ID' => 789, 'TITLE' => 'pish-pish'),
     * )
     * </code>
     * Результат будет выводиться в комбобоксе
     * @return array
     */
    protected function getVariants()
    {
        $variants = array();

        $dbRes = TablesOperator::getTablesList();
        while ($item = $dbRes->fetch()) {
            $tableName = array_pop($item);
            $variants[$tableName] = $tableName;
        }

        return $variants;
    }
}