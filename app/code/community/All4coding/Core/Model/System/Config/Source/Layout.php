<?php
/**
 * Core Config Source Layout
 *
 * @category   All4coding
 * @package    All4coding_Core
 * @author     All For Coding <info@all4coding.com>
 */
class All4coding_Core_Model_System_Config_Source_Layout extends Mage_Page_Model_Source_Layout
{
    /**
     * Retrieve page layout options array
     *
     * @return array
     */
    public function toOptionArray($withEmpty = true)
    {
        return parent::toOptionArray(true);
    }
}