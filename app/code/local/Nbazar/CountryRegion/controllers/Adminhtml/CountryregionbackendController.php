<?php
class Nbazar_CountryRegion_Adminhtml_CountryregionbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Country Region"));
	   $this->renderLayout();
    }
}