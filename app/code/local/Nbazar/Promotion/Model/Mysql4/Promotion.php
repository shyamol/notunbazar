<?php
class Nbazar_Promotion_Model_Mysql4_Promotion extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("promotion/promotion", "promotion_id");
    }
}