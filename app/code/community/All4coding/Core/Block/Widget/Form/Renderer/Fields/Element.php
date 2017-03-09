<?php
/**
 * Fields element renderer
 *
 * @category   All4coding
 * @package    All4coding_Core
 * @author     All For Coding <info@all4coding.com>
 */
class All4coding_Core_Block_Widget_Form_Renderer_Fields_Element extends Mage_Core_Block_Template implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_element;

    protected function _construct()
    {
        $this->setTemplate('all4coding/core/widget/form/renderer/fields/element.phtml');
    }

    public function getElement()
    {
        return $this->_element;
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->_element = $element;
        return $this->toHtml();
    }
}
