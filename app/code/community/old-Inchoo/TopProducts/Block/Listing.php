<?php

/**
 * @category     Inchoo
 * @package     Inchoo Top Products
 * @author        Domagoj Potkoc, Inchoo Team <web@inchoo.net>
 * @modified    Mladen Lotar <mladen.lotar@surgeworks.com>, Vedran Subotic <vedran.subotic@surgeworks.com>
 */
class Inchoo_TopProducts_Block_Listing extends Mage_Catalog_Block_Product_Abstract {
    /*
     * Check sort option and limits set in System->Configuration and apply them
     * Additionally, set template to block so call from CMS will look like {{block type="topproducts/listing"}}
     */

    public function __construct() {

        $this->setTemplate('inchoo/topproducts/block_top_products.phtml');

        $this->setLimit((int) Mage::getStoreConfig("topproducts/cmspage/number_of_items"));
        $sort_by = Mage::getStoreConfig("topproducts/cmspage/product_sort_by");
        $this->setItemsPerRow((int) Mage::getStoreConfig("topproducts/cmspage/number_of_items_per_row"));

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
     * Load top products collection
     * */

    protected function _beforeToHtml() {
        $layer = Mage::getSingleton('catalog/layer');
        $_category = $layer->getCurrentCategory();
        $currentCategoryId= $_category->getId();
        $category_model = Mage::getModel('catalog/category'); 
        $_category = $category_model->load($currentCategoryId); 
        $all_child_categories = $category_model->getResource()->getAllChildren($_category);
        $string=implode(",",$all_child_categories);
        $collection = Mage::getResourceModel('catalog/product_collection');

        $attributes = Mage::getSingleton('catalog/config')
                ->getProductAttributes();

        $collection->addAttributeToSelect($attributes)
                ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left') //for categry filter
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToFilter('inchoo_top_product', 1, 'left')
                ->addAttributeToFilter('category_id', $all_child_categories) //for categry filter
                ->addStoreFilter()
                ->getSelect()->order($this->getSortBy())->limit($this->getLimit());

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        $this->_productCollection = $collection;

        $this->setProductCollection($collection);
        return parent::_beforeToHtml();
    }

    protected function _toHtml() {

        if (!$this->helper('topproducts')->getIsActive()) {
            return '';
        }

        return parent::_toHtml();
    }

    /*
     * Return label for CMS block output
     * */

    protected function getBlockLabel() {
        return $this->helper('topproducts')->getCmsBlockLabel();
    }

}