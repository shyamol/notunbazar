<?php
/**
 * Product collection
 *
 * @category   All4coding
 * @package    All4coding_Core
 * @author     All For Coding <info@all4coding.com>
 */
class All4coding_Core_Model_Resource_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection {
    public function getAttributeFieldName($attributeCode)
    {
        return $this->_getAttributeFieldName($attributeCode);
    }
    
    public function getOrder()
    {
        return $this->_orders;
    }
}
