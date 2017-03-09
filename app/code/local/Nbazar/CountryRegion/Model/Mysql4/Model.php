<?php
class Nbazar_CountryRegion_Model_Mysql4_Model extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("countryregion/model", "country_region_id");
    }
}