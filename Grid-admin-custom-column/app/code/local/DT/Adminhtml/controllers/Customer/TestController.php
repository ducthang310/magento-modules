<?php

class DT_Adminhtml_Customer_TestController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Customer Test'));
        $this->loadLayout();
        $this->_setActiveMenu('customer/customer');
        $this->_addContent($this->getLayout()->createBlock('dt_adminhtml/customer_test'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('dt_adminhtml/customer_test_grid')->toHtml()
        );
    }

    public function checkAction() {
        $name = $this->getRequest()->getParam('customer_name', 'Test Name');
        $jsonData = array('result' => $name[0] === 'A' ? true : false);
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($jsonData));
    }

    protected function _checkCustomerName($name) {
        return $name[0] === 'A' ? true : false;
    }
}
