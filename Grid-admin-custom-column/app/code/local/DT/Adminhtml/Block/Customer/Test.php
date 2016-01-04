<?php
class DT_Adminhtml_Block_Customer_Test extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'dt_adminhtml'; // Tên c?a block group c?a module hi?n t?i
        $this->_controller = 'customer_test'; // cái này s? dùng làm ???ng d?n ?? g?i ra block con DT_Adminhtml_Block_Customer_Test_Grid nên ko th? ??t tên tùy ti?n ???c
        $this->_headerText = Mage::helper('dt_adminhtml')->__('List Customer');

        parent::__construct();
        $this->_removeButton('add'); // B? nút add c?a template Grid ?i
    }
}