<?php

/**
 * @category     Inchoo
 * @package     Inchoo Featured Products
 * @author        Domagoj Potkoc, Inchoo Team <web@inchoo.net>
 * @modified    Mladen Lotar <mladen.lotar@surgeworks.com>, Vedran Subotic <vedran.subotic@surgeworks.com>
 */
class Inchoo_PendingProducts_IndexController extends Mage_Core_Controller_Front_Action {
    /*
     * Check settings set in System->Configuration and apply them for featured-products page
     * */

    public function indexAction() {

        if (!Mage::helper('featuredproducts')->getIsActive()) {
            $this->_forward('noRoute');
            return;
        }

        $template = Mage::getConfig()->getNode('global/page/layouts/' . Mage::getStoreConfig("pendingproducts/standalone/layout") . '/template');

        $this->loadLayout();

        $this->getLayout()->getBlock('root')->setTemplate($template);
        $this->getLayout()->getBlock('head')->setTitle($this->__(Mage::getStoreConfig("pendingproducts/standalone/meta_title")));
        $this->getLayout()->getBlock('head')->setDescription($this->__(Mage::getStoreConfig("pendingproducts/standalone/meta_description")));
        $this->getLayout()->getBlock('head')->setKeywords($this->__(Mage::getStoreConfig("pendingproducts/standalone/meta_keywords")));

        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbsBlock->addCrumb('featured_products', array(
            'label' => Mage::helper('inchoo_pendingproducts')->__(Mage::helper('pendingproducts')->getPageLabel()),
            'title' => Mage::helper('inchoo_pendingproducts')->__(Mage::helper('pendingproducts')->getPageLabel()),
        ));

        $this->renderLayout();
    }

}