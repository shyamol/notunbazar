<?php
	
class Nbazar_CountryRegion_Block_Adminhtml_Model_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "country_region_id";
				$this->_blockGroup = "countryregion";
				$this->_controller = "adminhtml_model";
				$this->_updateButton("save", "label", Mage::helper("countryregion")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("countryregion")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("countryregion")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("model_data") && Mage::registry("model_data")->getId() ){

				    return Mage::helper("countryregion")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("model_data")->getId()));

				} 
				else{

				     return Mage::helper("countryregion")->__("Add Item");

				}
		}
}