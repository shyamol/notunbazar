<?php
/**
 * Core Feed Model
 *
 * @category   All4coding
 * @package    All4coding_Core
 * @author     All For Coding <info@all4coding.com>
 */

class All4coding_Core_Model_Feed extends Mage_AdminNotification_Model_Feed
{
    const XML_FEED_ENABLED      = 'a4c_core/notification/feed_enabled';
    const XML_USE_HTTPS_PATH    = 'a4c_core/notification/use_https';
    const XML_FEED_URL_PATH     = 'a4c_core/notification/feed_url';
    const XML_FREQUENCY_PATH    = 'a4c_core/notification/frequency';
    const XML_LAST_UPDATE_PATH  = 'a4c_core/notification/last_update';
    
    /**
     * Check feed for notification
     *
     * @return All4coding_Core_Model_Feed
     */
    public function checkUpdate()
    {
        if(Mage::getStoreConfig(self::XML_FEED_ENABLED)){
            parent::checkUpdate();
        }
        return $this;
    }
    
    /**
     * Retrieve Last update time
     *
     * @return int
     */
    public function getLastUpdate()
    {
        return Mage::app()->loadCache('all4coding_notifications_lastcheck');
    }
    
    /**
     * Set last update time (now)
     *
     * @return All4coding_Core_Model_Feed
     */
    public function setLastUpdate()
    {
        Mage::app()->saveCache(time(), 'all4coding_notifications_lastcheck');
        return $this;
    }
    
    /**
     * Retrieve feed url
     *
     * @return string
     */
    public function getFeedUrl()
    {
        if (is_null($this->_feedUrl)) {
            $this->_feedUrl = (Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://')
                . Mage::getStoreConfig(self::XML_FEED_URL_PATH);
        }
        return $this->_feedUrl;
    }
    
    /**
     * Retrieve Update Frequency
     *
     * @return int
     */
    public function getFrequency()
    {
        return Mage::getStoreConfig(self::XML_FREQUENCY_PATH) * 3600;
    }
    
}