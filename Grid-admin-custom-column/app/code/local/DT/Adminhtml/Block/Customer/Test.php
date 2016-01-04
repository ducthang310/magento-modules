<?php
class DT_Adminhtml_Block_Customer_Test extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'dt_adminhtml'; // T�n c?a block group c?a module hi?n t?i
        $this->_controller = 'customer_test'; // c�i n�y s? d�ng l�m ???ng d?n ?? g?i ra block con DT_Adminhtml_Block_Customer_Test_Grid n�n ko th? ??t t�n t�y ti?n ???c
        $this->_headerText = Mage::helper('dt_adminhtml')->__('List Customer');

        parent::__construct();
        $this->_removeButton('add'); // B? n�t add c?a template Grid ?i
    }
}