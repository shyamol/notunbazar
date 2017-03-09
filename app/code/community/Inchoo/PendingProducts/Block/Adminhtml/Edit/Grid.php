<?php

/**
 * @category     Inchoo
 * @package     Inchoo Featured Products
 * @author        Domagoj Potkoc, Inchoo Team <web@inchoo.net>
 * @modified    Mladen Lotar <mladen.lotar@surgeworks.com>, Vedran Subotic <vedran.subotic@surgeworks.com>
 */
class Inchoo_PendingProducts_Block_Adminhtml_Edit_Grid extends Mage_Adminhtml_Block_Widget_Grid {

     public function __construct()
    {
        parent::__construct();
        $this->setId('inchoo_pending_products');
        $this->setDefaultSort('increment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {
         //$store = $this->_getStore();

         $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('status')
                ->addAttributeToSelect('inchoo_top_product')
                ->addAttributeToSelect('inchoo_featured_product')
                ->addAttributeToSelect('type_id')
                ->addAttributeToFilter('visibility', array('nin' => array(1, 3)))
                ->addAttributeToFilter('status',array('eq' => Mage_Catalog_Model_Product_Status::STATUS_PENDING));


      
 
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
 
    protected function _prepareColumns() {

        $action_name = $this->getRequest()->getActionName();

        

        $this->addColumn('entity_id', array(
            'header' => Mage::helper('catalog')->__('ID'),
            'sortable' => true,
            'width' => '60',
            'index' => 'entity_id',
            'type' => 'number'
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('catalog')->__('Name'),
            'index' => 'name',
            'renderer' => 'inchoo_pendingproducts/adminhtml_edit_renderer_name',
        ));
        $this->addColumn('inchoo_featured_product', array(
            'header' => Mage::helper('catalog')->__('Featured'),
            'width' => '140',
            'index' => 'inchoo_featured_product',
            'filter' => false,
            //'renderer' => 'pendingproducts/adminhtml_edit_renderer_visibility',
        ));
        $this->addColumn('inchoo_top_product', array(
            'header' => Mage::helper('catalog')->__('Top'),
            'width' => '140',
            'index' => 'inchoo_top_product',
            'filter' => false,
            //'renderer' => 'pendingproducts/adminhtml_edit_renderer_visibility',
        ));

        $this->addColumn('type', array(
            'header' => Mage::helper('catalog')->__('Type'),
            'width' => '60px',
            'index' => 'type_id',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));

        $this->addColumn('sku', array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'width' => '140',
            'index' => 'sku'
        ));
         $this->addColumn('status',
            array(
                'header'=> Mage::helper('catalog')->__('Status'),
                'width' => '70px',
                'index' => 'status',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
            ));
        $this->addColumn('created_at', array(
             'header'    => Mage::helper('customer')->__('Created at'),
             'type'      => 'date',  // <-- change to date
             //'format'    => 'Y.m.d',
             'index'     => 'created_at',
        ));

        // $this->addColumn('visibility', array(
        //     'header' => Mage::helper('catalog')->__('Visibility'),
        //     'width' => '140',
        //     'index' => 'visibility',
        //     'filter' => false,
        //     //'renderer' => 'pendingproducts/adminhtml_edit_renderer_visibility',
        // ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('websites', array(
                'header' => Mage::helper('catalog')->__('Websites'),
                'width' => '100px',
                'sortable' => false,
                'index' => 'websites',
                'type' => 'options',
                'options' => Mage::getModel('core/website')->getCollection()->toOptionHash(),
            ));
        }

       // $store = $this->_getStore();
        // $this->addColumn('price', array(
        //     'header' => Mage::helper('catalog')->__('Price'),
        //     'type' => 'price',
        //     'currency_code' => $store->getBaseCurrency()->getCode(),
        //     'index' => 'price',
        // ));

        $this->addExportType('*/*/exportCsv', Mage::helper('inchoo_pendingproducts')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('inchoo_pendingproducts')->__('Excel XML'));

        return parent::_prepareColumns();
    }
 
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

}