<?php
/**
 * Adminhtml grid item renderer
 *
 * @category   All4coding
 * @package    All4coding_Core
 * @author     All For Coding <info@all4coding.com>
 */
class All4coding_Core_Block_Widget_Grid_Column_Renderer_TextIndent extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text {
    /**
     * Renders grid column
     *
     * @param Varien_Object $row
     * @return mixed
     */
    public function _getValue(Varien_Object $row)
    {
        $indentText = '';
        if ($this->getColumn()->getIndent()) {
            $indentText = str_repeat('<span class="gi">|&mdash;</span>', $row->getData('level')-1);
        }
        
        return $indentText.parent::_getValue($row);
    }
}