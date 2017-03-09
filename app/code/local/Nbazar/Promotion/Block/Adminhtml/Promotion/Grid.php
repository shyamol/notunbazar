<?php

class Nbazar_Promotion_Block_Adminhtml_Promotion_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId("promotionGrid");
        $this->setDefaultSort("promotion_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel("promotion/promotion")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn("promotion_id", array(
            "header" => Mage::helper("promotion")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "promotion_id",
        ));

        $this->addColumn("product_id", array(
            "header" => Mage::helper("promotion")->__("Product ID"),
            "index" => "product_id",
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('promotion')->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => Nbazar_Promotion_Block_Adminhtml_Promotion_Grid::getOptionArray5(),
        ));
        $this->addColumn('adtype', array(
            'header' => Mage::helper('promotion')->__('AD Type'),
            'index' => 'adtype',
            'type' => 'options',
            'options' => Nbazar_Promotion_Block_Adminhtml_Promotion_Grid::getOptionArray1(),
        ));

        $this->addColumn("customer_id", array(
            "header" => Mage::helper("promotion")->__("Customer ID"),
            "index" => "customer_id",
        ));
        $this->addColumn("order_id", array(
            "header" => Mage::helper("promotion")->__("Order ID"),
            "index" => "order_id",
        ));
        $this->addColumn('order_status', array(
            'header' => Mage::helper('promotion')->__('Order Status'),
            'index' => 'order_status',
            'type' => 'options',
            'options' => Nbazar_Promotion_Block_Adminhtml_Promotion_Grid::getOptionArray4(),
        ));

        $this->addColumn('active_from', array(
            'header' => Mage::helper('promotion')->__('Active From'),
            'index' => 'active_from',
            'type' => 'datetime',
        ));
        $this->addColumn('active_to', array(
            'header' => Mage::helper('promotion')->__('Active To'),
            'index' => 'active_to',
            'type' => 'datetime',
        ));
        $this->addColumn('created_at', array(
            'header' => Mage::helper('promotion')->__('Created Date'),
            'index' => 'created_at',
            'type' => 'datetime',
        ));
        $this->addColumn('updated_at', array(
            'header' => Mage::helper('promotion')->__('Update Date'),
            'index' => 'updated_at',
            'type' => 'datetime',
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('promotion_id');
        $this->getMassactionBlock()->setFormFieldName('promotion_ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_promotion', array(
            'label' => Mage::helper('promotion')->__('Remove Promotion'),
            'url' => $this->getUrl('*/adminhtml_promotion/massRemove'),
            'confirm' => Mage::helper('promotion')->__('Are you sure?')
        ));
        
        $statuses= Nbazar_Promotion_Block_Adminhtml_Promotion_Grid::getOptionArray5();
        
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('promotion')->__('Change status'),
            'url' => $this->getUrl('*/adminhtml_promotion/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                'name' => 'status',
                'type' => 'select',
                'class' => 'required-entry',
                'label' => Mage::helper('promotion')->__('Status'),
                'values'=> $statuses,
            ))
        ));
        
        return $this;
    }
    static public function getOptionArray5() {
        $data_array = array();
        $data_array[1] = 'Enable';
        $data_array[2] = 'Disable';
        return($data_array);
    }
    static public function getValueArray5() {
        $data_array = array();
        foreach (Nbazar_Promotion_Block_Adminhtml_Promotion_Grid::getOptionArray5() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return($data_array);
    }
    static public function getOptionArray1() {
        $data_array = array();
        $data_array[1] = 'Feature';
        $data_array[2] = 'Top';
        return($data_array);
    }

    static public function getValueArray1() {
        $data_array = array();
        foreach (Nbazar_Promotion_Block_Adminhtml_Promotion_Grid::getOptionArray1() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return($data_array);
    }

    static public function getOptionArray4() {
        $data_array = array();
        $data_array[1] = 'Pending';
        $data_array[2] = 'On Process';
        $data_array[3] = 'Complete';
        return($data_array);
    }

    static public function getValueArray4() {
        $data_array = array();
        foreach (Nbazar_Promotion_Block_Adminhtml_Promotion_Grid::getOptionArray4() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return($data_array);
    }    

}