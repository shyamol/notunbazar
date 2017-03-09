<?php

/**
 * @category     Inchoo
 * @package     Inchoo Top Products
 * @author        Domagoj Potkoc, Inchoo Team <web@inchoo.net>
 * @modified    Mladen Lotar <mladen.lotar@surgeworks.com>, Vedran Subotic <vedran.subotic@surgeworks.com>
 */
class Inchoo_TopProducts_IndexController extends Mage_Core_Controller_Front_Action {
    /*
     * Check settings set in System->Configuration and apply them for top-products page
     * */

    public function indexAction() {

        if (!Mage::helper('topproducts')->getIsActive()) {
            $this->_forward('noRoute');
            return;
        }

        $template = Mage::getConfig()->getNode('global/page/layouts/' . Mage::getStoreConfig("topproducts/standalone/layout") . '/template');

        $this->loadLayout();

        $this->getLayout()->getBlock('root')->setTemplate($template);
        $this->getLayout()->getBlock('head')->setTitle($this->__(Mage::getStoreConfig("topproducts/standalone/meta_title")));
        $this->getLayout()->getBlock('head')->setDescription($this->__(Mage::getStoreConfig("topproducts/standalone/meta_description")));
        $this->getLayout()->getBlock('head')->setKeywords($this->__(Mage::getStoreConfig("topproducts/standalone/meta_keywords")));

        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbsBlock->addCrumb('top_products', array(
            'label' => Mage::helper('topproducts')->__(Mage::helper('topproducts')->getPageLabel()),
            'title' => Mage::helper('topproducts')->__(Mage::helper('topproducts')->getPageLabel()),
        ));

        $this->renderLayout();
    }

}