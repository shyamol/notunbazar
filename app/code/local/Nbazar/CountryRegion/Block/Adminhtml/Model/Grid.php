<?php

class Nbazar_CountryRegion_Block_Adminhtml_Model_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId("modelGrid");
        $this->setDefaultSort("country_region_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel("countryregion/model")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn("country_region_id", array(
            "header" => Mage::helper("countryregion")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "country_region_id",
        ));

        $this->addColumn('country_id', array(
            'header' => Mage::helper('countryregion')->__('Country'),
            'index' => 'country_id',
            'type' => 'options',
            'options' => Nbazar_CountryRegion_Block_Adminhtml_Model_Grid::getOptionArray0(),
        ));

        $this->addColumn('state_id', array(
            'header' => Mage::helper('countryregion')->__('District/State'),
            'index' => 'state_id',
            'type' => 'options',
            'options' => Nbazar_CountryRegion_Block_Adminhtml_Model_Grid::getOptionArray1(),
        ));

        $this->addColumn('region_id', array(
            'header' => Mage::helper('countryregion')->__('Region/Thana/City'),
            'index' => 'region_id',
            'type' => 'options',
            'options' => Nbazar_CountryRegion_Block_Adminhtml_Model_Grid::getOptionArray2(),
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('country_region_id');
        $this->getMassactionBlock()->setFormFieldName('country_region_ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_model', array(
            'label' => Mage::helper('countryregion')->__('Remove Model'),
            'url' => $this->getUrl('*/adminhtml_model/massRemove'),
            'confirm' => Mage::helper('countryregion')->__('Are you sure?')
        ));
        return $this;
    }

    static public function getOptionArray0() {
        
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $result = $read->query("SELECT t1.option_id as id, t2.value as country FROM ntnbz_eav_attribute_option as t1 INNER JOIN ntnbz_eav_attribute_option_value as t2 where t1.attribute_id = 146 and t2.store_id = 0 and t1.option_id = t2.option_id ORDER BY country ASC");
                
        $data_array = array();
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $data_array[$row['id']] = $row["country"];
        }
        
        //$data_array[0] = 'Bangladesh';
        //$data_array[1] = 'USA';
        return($data_array);
    }

    static public function getValueArray0() {
        $data_array = array();
        foreach (Nbazar_CountryRegion_Block_Adminhtml_Model_Grid::getOptionArray0() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return($data_array);
    }

    static public function getOptionArray1() {        
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $result = $read->query("SELECT t1.option_id as id, t2.value as district FROM ntnbz_eav_attribute_option as t1 INNER JOIN ntnbz_eav_attribute_option_value as t2 where t1.attribute_id = 147 and t2.store_id = 0 and t1.option_id = t2.option_id ORDER BY district ASC");
        
        $data_array = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $data_array[$row['id']] = $row["district"];
        }
        //$data_array[0] = 'Dhaka';
        //$data_array[1] = 'Satkhira';
        return($data_array);
    }

    static public function getValueArray1() {
        $data_array = array();
        foreach (Nbazar_CountryRegion_Block_Adminhtml_Model_Grid::getOptionArray1() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return($data_array);
    }

    static public function getOptionArray2() {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $result = $read->query("SELECT t1.option_id as id, t2.value as city FROM ntnbz_eav_attribute_option as t1 INNER JOIN ntnbz_eav_attribute_option_value as t2 where t1.attribute_id = 148 and t2.store_id = 0 and t1.option_id = t2.option_id ORDER BY city ASC");
        
        $data_array = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $data_array[$row['id']] = $row["city"];
        }
//        $data_array[0] = 'Badda';
//        $data_array[1] = 'Shyamnagar';
        return($data_array);
    }

    static public function getValueArray2() {
        $data_array = array();
        foreach (Nbazar_CountryRegion_Block_Adminhtml_Model_Grid::getOptionArray2() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return($data_array);
    }

}
