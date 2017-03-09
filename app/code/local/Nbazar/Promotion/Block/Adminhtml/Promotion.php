<?php


class Nbazar_Promotion_Block_Adminhtml_Promotion extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_promotion";
	$this->_blockGroup = "promotion";
	$this->_headerText = Mage::helper("promotion")->__("Promotion Manager");
	$this->_addButtonLabel = Mage::helper("promotion")->__("Add New Item");
	parent::__construct();
	
	}

}