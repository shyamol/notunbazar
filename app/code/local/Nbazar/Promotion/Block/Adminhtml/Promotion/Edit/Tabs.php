<?php
class Nbazar_Promotion_Block_Adminhtml_Promotion_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("promotion_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("promotion")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("promotion")->__("Item Information"),
				"title" => Mage::helper("promotion")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("promotion/adminhtml_promotion_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
