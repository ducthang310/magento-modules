<?php
class DT_Adminhtml_Block_Customer_Test_Renderer_Check extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $id =  $row->getId();
        $html = '
        <input style="width: 90px;" type="text" class="input-text " value="" name="check_input_' . $id . '" id="check_input_' . $id . '">
        <a href="javascript:void(0)" style="text-decoration: none;" class="form-button" onclick="checkName(\'check_input_' . $id . '\', \'' . $row->getName() . '\')">Check</a>
        ';

        return $html;
    }
}