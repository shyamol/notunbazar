<?php

class Abac_Classified_EditController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {


        $this->loadLayout();
        $this->getLayout()->getBlock("head")->setTitle($this->__("Edit Advertisement"));

        $this->renderLayout();

    }
    public function blahAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

}