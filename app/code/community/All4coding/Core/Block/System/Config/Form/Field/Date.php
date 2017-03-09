<?php
/**
 * Core system config Date field renderer
 *
 * @category    All4coding
 * @package     All4coding_Core
 * @author      All For Coding <info@all4coding.com>
 */
class All4coding_Core_Block_System_Config_Form_Field_Date extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
        $element->setFormat(
            Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
        );
        return $element->getElementHtml();
    }
}