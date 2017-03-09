<?php
class Nbazar_Promotion_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
    	// echo "string";
    	// die();
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Promote your product"));
//	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
//      $breadcrumbs->addCrumb("home", array(
//                "label" => $this->__("Home Page"),
//                "title" => $this->__("Home Page"),
//                "link"  => Mage::getBaseUrl()
//		   ));

//      $breadcrumbs->addCrumb("promote your product", array(
//                "label" => $this->__("Promote your product"),
//                "title" => $this->__("Promote your product")
//		   ));

      $this->renderLayout(); 
	  
    }
}