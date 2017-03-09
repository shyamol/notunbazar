<?php

class Abac_Classified_SendController extends Mage_Core_Controller_Front_Action{
    
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }
    
    public function IndexAction() {

	  $this->loadLayout();
	  $this->_initLayoutMessages('customer/session');   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Message to Advertiser"));

      $this->renderLayout(); 
	  
    }


}

