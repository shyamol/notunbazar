<?php
class Nbazar_Promotion_Adminhtml_PromotionbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Promotional Prodct"));
	   $this->renderLayout();
    }
}