<?php

/**
 * @category     Inchoo
 * @package     Inchoo Featured Products
 * @author        Domagoj Potkoc, Inchoo Team <web@inchoo.net>
 * @modified    Mladen Lotar <mladen.lotar@surgeworks.com>, Vedran Subotic <vedran.subotic@surgeworks.com>
 */
class Inchoo_FeaturedProducts_Block_Listing extends Mage_Catalog_Block_Product_Abstract {
    /*
     * Check sort option and limits set in System->Configuration and apply them
     * Additionally, set template to block so call from CMS will look like {{block type="featuredproducts/listing"}}
     */

    public function __construct() {

        $this->setTemplate('inchoo/featuredproducts/block_featured_products.phtml');

        $this->setLimit((int) Mage::getStoreConfig("featuredproducts/cmspage/number_of_items"));
        $sort_by = Mage::getStoreConfig("featuredproducts/cmspage/product_sort_by");
        $this->setItemsPerRow((int) Mage::getStoreConfig("featuredproducts/cmspage/number_of_items_per_row"));

        switch ($sort_by) {
            case 0:
                $this->setSortBy("rand()");
                break;
            case 1:
                $this->setSortBy("created_at desc");
                break;
            default:
                $this->setSortBy("rand()");
        }
    }

    /*
     * Load featured products collection
     * */

    protected function _beforeToHtml() {
        $collection = Mage::getResourceModel('catalog/product_collection');

        $attributes = Mage::getSingleton('catalog/config')
                ->getProductAttributes();
               $collection = Mage::getModel('catalog/product')->getCollection();

                $promotionTable = Mage::getSingleton('core/resource')->getTableName('promotion');

         $collection->addAttributeToSelect($attributes)
         ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
      
        
        $collection->getSelect()->join(
            array('abc' => $promotionTable), "e.entity_id = abc.product_id", array('abc.*')
            )
        ->where("abc.order_status = 3 AND abc.adtype = 1" );


         Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
         Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        

         $this->_productCollection = $collection;

        $this->setProductCollection($collection);
        return parent::_beforeToHtml();
    }

    protected function _toHtml() {

        if (!$this->helper('featuredproducts')->getIsActive()) {
            return '';
        }

        return parent::_toHtml();
    }

    /*
     * Return label for CMS block output
     * */

    protected function getBlockLabel() {
        return $this->helper('featuredproducts')->getCmsBlockLabel();
    }

}