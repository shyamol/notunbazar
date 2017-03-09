<?php

/**
 * @category     Inchoo
 * @package     Inchoo Featured Products
 * @author        Domagoj Potkoc, Inchoo Team <web@inchoo.net>
 * @modified    Mladen Lotar <mladen.lotar@surgeworks.com>, Vedran Subotic <vedran.subotic@surgeworks.com>
 */
class Inchoo_PendingProducts_Helper_Data extends Mage_Core_Helper_Abstract {

    const PATH_PAGE_HEADING = 'inchoo_pendingproducts/standalone/heading';
    const PATH_CMS_HEADING = 'inchoo_pendingproducts/cmspage/heading_block';
    const DEFAULT_LABEL = 'Pending Products';

    public function getCmsBlockLabel() {
        $configValue = Mage::getStoreConfig(self::PATH_CMS_HEADING);
        return strlen($configValue) > 0 ? $configValue : self::DEFAULT_LABEL;
    }

    public function getPageLabel() {
        $configValue = Mage::getStoreConfig(self::PATH_PAGE_HEADING);
        return strlen($configValue) > 0 ? $configValue : self::DEFAULT_LABEL;
    }

    public function getIsActive() {
        return (bool) Mage::getStoreConfig('inchoo_pendingproducts/general/active');
    }

}