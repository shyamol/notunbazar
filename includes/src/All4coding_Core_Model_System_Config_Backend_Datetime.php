<?php
/**
 * Core System Config Backend Datetime
 *
 * @category    All4coding
 * @package     All4coding_Core
 * @author      All For Coding <info@all4coding.com>
 */
class All4coding_Core_Model_System_Config_Backend_Datetime extends Mage_Core_Model_Config_Data
{
    /**
     * Formating date value before save
     * 
     * @throws Mage_Eav_Exception
     */
    protected function _beforeSave()
    {
        $value = $this->getValue();
        // need to check Mage_Eav_Model_Entity_Attribute_Backend_Datetime
        if ($value) {
            try {
                $value = $this->formatDate($value);
            } catch (Exception $e) {
                Mage::throwException(Mage::helper('all4coding_core')->__('%s: Invalid date', $this->getFieldConfig()->label));
            }
            $this->setValue($value);
        }
    }
    
    /**
     * Prepare date for save in DB
     *
     * string format used from input fields (all date input fields need apply locale settings)
     * int value can be declared in code (this meen whot we use valid date)
     *
     * @param   string | int $date
     * @return  string
     */
    public function formatDate($date)
    {
        if (empty($date)) {
            return null;
        }
        // unix timestamp given - simply instantiate date object
        if (preg_match('/^[0-9]+$/', $date)) {
            $date = new Zend_Date((int)$date);
        }
        // international format
        else if (preg_match('#^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?$#', $date)) {
            $zendDate = new Zend_Date();
            $date = $zendDate->setIso($date);
        }
        // parse this date in current locale, do not apply GMT offset
        else {
            $date = Mage::app()->getLocale()->date($date,
               Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
               null, false
            );
        }
        return $date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
    }
    
}