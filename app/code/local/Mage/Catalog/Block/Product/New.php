<?php

class Mage_Catalog_Block_Product_New extends Mage_Catalog_Block_Product_List
{
   function get_prod_count()
   {
      //unset any saved limits
      Mage::getSingleton('catalog/session')->unsLimitPage();
      return (isset($_REQUEST['limit'])) ? intval($_REQUEST['limit']) : 12;
   }// get_prod_count

   function get_cur_page()
   {
      return (isset($_REQUEST['p'])) ? intval($_REQUEST['p']) : 1;
   }// get_cur_page

   /**
    * Retrieve loaded category collection
    *
    * @return Mage_Eav_Model_Entity_Collection_Abstract
   **/
   protected function _getProductCollection()
   {
      $todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

      $collection = Mage::getResourceModel('catalog/product_collection');
      $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());

      /* $collection = $this->_addProductAttributesAndPrices($collection)
         ->addStoreFilter()
         ->addAttributeToFilter('news_from_date', array('date' => true, 'to' => $todayDate))
         ->addAttributeToFilter('news_to_date', array('or'=> array(
            0 => array('date' => true, 'from' => $todayDate),
            1 => array('is' => new Zend_Db_Expr('null')))
         ), 'left')
         ->addAttributeToSort('news_from_date', 'desc')
         ->setPageSize($this->get_prod_count())
         ->setCurPage($this->get_cur_page());
 */
      $collection = $this->_addProductAttributesAndPrices($collection)
         ->addStoreFilter()
         ->setPageSize($this->get_prod_count())
         ->setCurPage($this->get_cur_page())
         //->setOrder('created_at', 'desc');
         ->addAttributeToSort('created_at', 'desc');

         
 
      $this->setProductCollection($collection);

      return $collection;
   }// _getProductCollection
}// Mage_Catalog_Block_Product_New
?>