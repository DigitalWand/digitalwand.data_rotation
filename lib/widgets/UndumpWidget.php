<?php

namespace DigitalWand\DataRotation\Widgets;

use DigitalWand\AdminHelper\Widget\StringWidget;

/**
 * Class ComboBoxWidget Выпадающий список
 * Доступные опции:
 * <ul>
 * <li> STYLE - inline-стили</li>
 * <li> VARIANTS - массив с вариантами занчений или функция для их получения</li>
 * <li> DEFAULT_VARIANT - ID варианта по-умолчанию</li>
 * </ul>
 */
class UndumpWidget extends StringWidget
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
        \CJSCore::Init(array("jquery"));
        $style = $this->getSettings('STYLE');

        $name = $forFilter ? $this->getFilterInputName() : $this->getEditInputName();

        if (isset($this->data['DIRECTION']) && $this->data['DIRECTION'] == 'import') {
            return '';
        }

        $result = "<input type='button' value='Применить дамп' style='" . $style . "' id='undump_button_" . $this->data['ID'] . "'/>";

        $result .= "
            <script type='text/javascript'>
                $(document).ready(function() {
                    $('#undump_button_" . $this->data['ID'] . "').click(function() {
                        $('#undump_button_" . $this->data['ID'] . "').attr('disabled', 'disabled');
                        $.post(
                            '',
                            {
                                ajaxAction: 'undump',
                                fileName: '" . $this->data['DUMP_FILE_NAME'] . "'
                            },
                            function (responce) {                           
                                $('#undump_button_" . $this->data['ID'] . "').attr('value', responce.status);
                            },
                            'json'
                        );
                    });
                });
            </script>
        ";

        return $result;
    }

    public function genListHTML(&$row, $data)
    {
        $row->AddViewField($this->getCode(), $this->genEditHTML());
    }
}