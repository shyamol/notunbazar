<?php
/**
 * Form Fields Element
 *
 * @category   All4coding
 * @package    All4coding_Core
 * @author     All For Coding <info@all4coding.com>
 */

class All4coding_Core_Block_Widget_Form_Element_Fields extends Varien_Data_Form_Element_Abstract
{
    static protected $_defaultFieldsElementRenderer;
     
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->setType('fields');
        $this->setHtmlContainerClass('fields');
        $this->setValueClass('fields');
        if (!self::$_defaultFieldsElementRenderer) {
            self::$_defaultFieldsElementRenderer = Mage::app()->getLayout()->createBlock('all4coding_core/widget_form_renderer_fields_element');
        }
    }

    public function getElementHtml()
    {
        $html = '';
        $html.= $this->getChildrenHtml();
        return $html;
    }
    
    public function getChildrenHtml()
    {
        $html = '';
        foreach ($this->getElements() as $element) {
            if ($element->getType() != 'fieldset') {
                $html.= $element->toHtml();
            }
        }
        return $html;
    }
    
    public function addField($elementId, $type, $config, $after=false)
    {
        $element = parent::addField($elementId, $type, $config, $after);
        if ($renderer = self::$_defaultFieldsElementRenderer) {
            $element->setRenderer($renderer);
        }
        return $element;
    }
}