<?php

namespace DigitalWand\DataRotation\Widgets;

use Bitrix\Main\Loader;
use DigitalWand\AdminHelper\Widget\ComboBoxWidget;

/**
 * Class ComboBoxWidget Выпадающий список
 * Доступные опции:
 * <ul>
 * <li> STYLE - inline-стили</li>
 * <li> VARIANTS - массив с вариантами занчений или функция для их получения</li>
 * <li> DEFAULT_VARIANT - ID варианта по-умолчанию</li>
 * </ul>
 */
class ColumnSelectWidget extends ComboBoxWidget
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
        $result = "<select name='" . $name . "' style='" . $style . "' ".($this->getSettings('ID') ? "id='".$this->getSettings("ID")."'" : "").">";
        $default = $this->getValue();
        if (is_null($default)) {
            $default = $this->getSettings('DEFAULT_VARIANT');
        }

        foreach ($variants as $id => $name) {
            $result .= "<option value='" . $id . "' " . ($id == $default ? "selected" : "") . ">" . $name . "</option>";
        }

        $result .= "</select>";
        
        $result .= "
            <script type='text/javascript'>
                var ".$this->getSettings("TARGET_ID")."_firstTime = true;
                var ".$this->getSettings("TARGET_ID")."_initDefault = true;
                $(document).ready(function() {
                    $('#".$this->getSettings("TARGET_ID")."').change(function() {
                        $.post(
                            '/bitrix/admin/ajax/digitalwand_data_rotation_get_fields.php',
                            {
                                tableName: $(this).val()
                            },
                            function (responce) {                           
                                $('#".$this->getSettings("ID")."').html('');
                                var html = '';
                                for (key in responce) {
                                    html += '<option value=' + responce[key].NAME +'>' + responce[key].NAME + ' ' + responce[key].TYPE +'</option>';
                                }
                                $('#".$this->getSettings("ID")."').html(html);
                                
                                if (".$this->getSettings("TARGET_ID")."_initDefault) {
                                    ".$this->getSettings("TARGET_ID")."_initDefault = false;
                                    $('#".$this->getSettings("ID")." option[value=".$default."]').attr('selected', 'selected');
                                }
                            },
                            'json'
                        );
                    });
                    if (".$this->getSettings("TARGET_ID")."_firstTime) {
                        ".$this->getSettings("TARGET_ID")."_firstTime = false;
                        $('#".$this->getSettings("TARGET_ID")."').change();
                    }
                });
            </script>
        ";

        return $result;
    }
}