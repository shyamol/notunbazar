<?php
/**
 * Core Config Source Design
 *
 * @category   All4coding
 * @package    All4coding_Core
 * @author     All For Coding <info@all4coding.com>
 */
class All4coding_Core_Model_System_Config_Source_Design extends Mage_Core_Model_Design_Source_Design {
    /**
     * Retrieve page layout options array
     *
     * @return array
     */
    public function toOptionArray($withEmpty = true)
    {
        return $this->getAllOptions(true);
    }
}