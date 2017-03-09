<?php
/**
 * @category     Inchoo
 * @package     Inchoo Top Products
 * @author        Domagoj Potkoc, Inchoo Team <web@inchoo.net>
 * @modified    Mladen Lotar <mladen.lotar@surgeworks.com>, Vedran Subotic <vedran.subotic@surgeworks.com>
 */
class Inchoo_TopProducts_Block_Product_List extends Mage_Catalog_Block_Product_List
{
	protected $_productCollection;
	protected $_sort_by;

        
        protected function _prepareLayout()
        {
            if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbsBlock->addCrumb('home', array(
                    'label'=>Mage::helper('catalog')->__('Home'),
                    'title'=>Mage::helper('catalog')->__('Go to Home Page'),
                    'link'=>Mage::getBaseUrl()
                ));
            }    
                            
            parent::_prepareLayout();
        }
        
	/*
	 * Remove "Position" option from Sort By dropdown
	 * */
	protected function _beforeToHtml()
	{
		parent::_beforeToHtml();
		$toolbar = $this->getToolbarBlock();
		$toolbar->removeOrderFromAvailableOrders('position');
		return $this;
	}


	/*
	 * Load top products collection
	 * */
	protected function _getProductCollection()
	{
           
		if (is_null($this->_productCollection)) {
                    $layer = Mage::getSingleton('catalog/layer');
                    $_category = $layer->getCurrentCategory();
                    $currentCategoryId= $_category->getId();
                    $category_model = Mage::getModel('catalog/category'); 
                    $_category = $category_model->load($currentCategoryId); 
                    $all_child_categories = $category_model->getResource()->getAllChildren($_category);
                    $string=implode(",",$all_child_categories);
                    $collection = Mage::getModel('catalog/product')->getCollection();

			$attributes = Mage::getSingleton('catalog/config')
				->getProductAttributes();

			$collection->addAttributeToSelect($attributes)
                                ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left') //for categry filter
				->addMinimalPrice()
				->addFinalPrice()
				->addTaxPercents()
				->addAttributeToFilter('inchoo_top_product', 1, 'left')
                                ->addAttributeToFilter('category_id', $string)//for categry filter
				->addStoreFilter();

			Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
			Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
			$this->_productCollection = $collection;
		}
		return $this->_productCollection;
}

    /**
     * Retrieve loaded top products collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getTopProductCollection()
    {
        return $this->_getProductCollection();
    }



   /**
     * Get HTML if there's anything to show
     */
	protected function _toHtml()
	{
		if ($this->_getProductCollection()->count()){
			return parent::_toHtml();
		}
		return '';
	}

}