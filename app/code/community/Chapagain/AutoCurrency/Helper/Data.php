<?php
/**
 * Loads GeoIP binary data files 
 *
 * @category   Chapagain
 * @package    Chapagain_AutoCurrency
 * @version    0.1.0
 * @author     Mukesh Chapagain <mukesh.chapagain@gmail.com> 
 * @link 	   http://blog.chapagain.com.np/category/magento/
 */
 
class Chapagain_AutoCurrency_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
     * Load GeoIP binary data file
     *
     * @return string
     */
	public function loadGeoIp() 
	{	
		// Load geoip.inc
		include_once(Mage::getBaseDir().'/var/geoip/geoip.inc');

		// Open Geo IP binary data file
		$geoIp = geoip_open(Mage::getBaseDir().'/var/geoip/GeoIP.dat',GEOIP_STANDARD);
		
		return $geoIp;
	}
	
	public function isEnabled()
    { 		
        return Mage::getStoreConfig('catalog/autocurrency/enable_disable');      
    }
	
	/**
     * Get IP Address
     *
     * @return string
     */
	public function getIpAddress() 
	{		
		return $_SERVER['REMOTE_ADDR'];
	}
}
