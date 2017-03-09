<?php
class Nbazar_CountryRegion_Block_Adminhtml_Model_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("countryregion_form", array("legend"=>Mage::helper("countryregion")->__("Item information")));

								
						 $fieldset->addField('country_id', 'select', array(
						'label'     => Mage::helper('countryregion')->__('Country'),
						'values'   => Nbazar_CountryRegion_Block_Adminhtml_Model_Grid::getValueArray0(),
						'name' => 'country_id',					
						"class" => "required-entry",
						"required" => true,
						));				
						 $fieldset->addField('state_id', 'select', array(
						'label'     => Mage::helper('countryregion')->__('District/State'),
						'values'   => Nbazar_CountryRegion_Block_Adminhtml_Model_Grid::getValueArray1(),
						'name' => 'state_id',					
						"class" => "required-entry",
						"required" => true,
						));				
						 $fieldset->addField('region_id', 'select', array(
						'label'     => Mage::helper('countryregion')->__('Region/Thana/City'),
						'values'   => Nbazar_CountryRegion_Block_Adminhtml_Model_Grid::getValueArray2(),
						'name' => 'region_id',					
						"class" => "required-entry",
						"required" => true,
						));

				if (Mage::getSingleton("adminhtml/session")->getModelData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getModelData());
					Mage::getSingleton("adminhtml/session")->setModelData(null);
				} 
				elseif(Mage::registry("model_data")) {
				    $form->setValues(Mage::registry("model_data")->getData());
				}
				return parent::_prepareForm();
		}
}
