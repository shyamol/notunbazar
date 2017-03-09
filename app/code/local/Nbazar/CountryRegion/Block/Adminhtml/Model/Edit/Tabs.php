<?php
class Nbazar_CountryRegion_Block_Adminhtml_Model_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("model_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("countryregion")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("countryregion")->__("Item Information"),
				"title" => Mage::helper("countryregion")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("countryregion/adminhtml_model_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
