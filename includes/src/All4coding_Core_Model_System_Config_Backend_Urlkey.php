<?php
/**
 * Core System Config Backend Urlkey
 *
 * @category    All4coding
 * @package     All4coding_Core
 * @author      All For Coding <info@all4coding.com>
 */
class All4coding_Core_Model_System_Config_Backend_Urlkey extends Mage_Core_Model_Config_Data
{
    /**
     * Formating url key value before save
     * 
     * @throws Mage_Eav_Exception
     */
    protected function _beforeSave()
    {
        $value = $this->getValue();
        if ($value) {
            if (!preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $value)) {
               Mage::throwException(Mage::helper('all4coding_core')->__('URL key contains capital letters or disallowed symbols.'));
            }
            if (preg_match('/^[0-9]+$/', $value)) {
                Mage::throwException(Mage::helper('all4coding_core')->__('URL key cannot consist only of numbers.'));
            }
        }
    }
}
