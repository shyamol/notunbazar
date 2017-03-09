<?php
class Abac_Classified_Adminhtml_ClassifiedbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("ABAC Classified"));
	   $this->renderLayout();
    }
}