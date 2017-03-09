<?php


class Nbazar_CountryRegion_Block_Adminhtml_Model extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_model";
	$this->_blockGroup = "countryregion";
	$this->_headerText = Mage::helper("countryregion")->__("Model Manager");
	$this->_addButtonLabel = Mage::helper("countryregion")->__("Add New Item");
	parent::__construct();
	
	}

}