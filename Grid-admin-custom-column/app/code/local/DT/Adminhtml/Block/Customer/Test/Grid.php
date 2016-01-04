<?php
class DT_Adminhtml_Block_Customer_Test_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('dtTestGrid');
        $this->setDefaultSort('entity_id');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setTemplate('dt_adminhtml/customer_grid.phtml');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToSelect('email')
            ->addAttributeToSelect('created_at')
            ->addAttributeToSelect('group_id')
            ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
            ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
            ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
            ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
            ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left');

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('customer')->__('ID'),
            'width'     => '50px',
            'index'     => 'entity_id',
            'type'  => 'number',
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('customer')->__('Name'),
            'index'     => 'name'
        ));
        $this->addColumn('email', array(
            'header'    => Mage::helper('customer')->__('Email'),
            'width'     => '150',
            'index'     => 'email'
        ));

        $groups = Mage::getResourceModel('customer/group_collection')
            ->addFieldToFilter('customer_group_id', array('gt'=> 0))
            ->load()
            ->toOptionHash();

        $this->addColumn('group', array(
            'header'    =>  Mage::helper('customer')->__('Group'),
            'width'     =>  '100',
            'index'     =>  'group_id',
            'type'      =>  'options',
            'options'   =>  $groups,
        ));

        $this->addColumn('Telephone', array(
            'header'    => Mage::helper('customer')->__('Telephone'),
            'width'     => '100',
            'index'     => 'billing_telephone'
        ));

        $this->addColumn('billing_postcode', array(
            'header'    => Mage::helper('customer')->__('ZIP'),
            'width'     => '90',
            'index'     => 'billing_postcode',
        ));

        $this->addColumn('billing_country_id', array(
            'header'    => Mage::helper('customer')->__('Country'),
            'width'     => '100',
            'type'      => 'country',
            'index'     => 'billing_country_id',
        ));

        $this->addColumn('billing_region', array(
            'header'    => Mage::helper('customer')->__('State/Province'),
            'width'     => '100',
            'index'     => 'billing_region',
        ));

        $this->addColumn('customer_since', array(
            'header'    => Mage::helper('customer')->__('Customer Since'),
            'type'      => 'datetime',
            'align'     => 'center',
            'index'     => 'created_at',
            'gmtoffset' => true
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('website_id', array(
                'header'    => Mage::helper('customer')->__('Website'),
                'align'     => 'center',
                'width'     => '80px',
                'type'      => 'options',
                'options'   => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(true),
                'index'     => 'website_id',
            ));
        }
        $this->addColumn('check_action', array(
            'header'    => Mage::helper('customer')->__('Check Action'),
            'id'        => 'entity_id',
            'width'     => '180',
            'renderer'  => 'DT_Adminhtml_Block_Customer_Test_Renderer_Check',// THIS IS WHAT THIS POST IS ALL ABOUT
            'filter'    => false,
            'sortable'  => false,
        ));

        $this->addColumn('loyal_customer', array(
            'header'    =>  Mage::helper('customer')->__('Loyal Customer'),
            'width'     =>  '30',
            'index'     =>  'loyal_customer',
            'type'      =>  'options',
            'options'   => array(
                '1' => Mage::helper('customer')->__('Have no complete orders'),
                '2' => Mage::helper('customer')->__('Have any complete orders')
            ),
            'sortable'  => false,
            'filter_condition_callback' => array($this, '_loyalCustomerFilter')
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    protected function _loyalCustomerFilter($collection, $column)
    {
        $filterValue = $column->getFilter()->getValue();

        // Get the resource model
        $resource = Mage::getSingleton('core/resource');
        // Retrieve the read connection
        $readConnection = $resource->getConnection('core_read');
        $orderTableName = Mage::getSingleton('core/resource')->getTableName('sales/order');
        $where = $readConnection->quoteInto('customer_id IS NOT NULL AND status = \'complete\'');
        $query = $readConnection->select()->from($orderTableName,array('customer_id'))->where($where)->group('customer_id');
        $customerIds = $readConnection->fetchAll($query);

        switch ($filterValue) {
            case '1':   // Have no complete orders
                $collection->addFieldToFilter('entity_id', array('nin' => $customerIds));
                break;
            case '2':   // Have any complete orders
                $collection->addFieldToFilter('entity_id', array('in' => $customerIds));
                break;
            default:
                break;
        }
    }
}