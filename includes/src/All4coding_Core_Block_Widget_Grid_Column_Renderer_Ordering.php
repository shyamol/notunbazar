<?php
/**
 * Adminhtml grid item renderer
 *
 * @category   All4coding
 * @package    All4coding_Core
 * @author     All For Coding <info@all4coding.com>
 */
class All4coding_Core_Block_Widget_Grid_Column_Renderer_Ordering extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text {
    /**
     * List of all ordering list get from grid
     * 
     * @var array
     */
    protected $_ordering = null;
    
    /**
     * Add Ordering JavaScript to Grid
     * 
     * @param $column Mage_Adminhtml_Block_Widget_Grid_Column
     */
    public function setColumn($column)
    {
        parent::setColumn($column);
        $grid = $column->getGrid();
        /* @var $grid Mage_Adminhtml_Block_Widget_Grid */
        if ($this->_ordering === null) {
            $this->_ordering = array();
            foreach ($grid->getCollection() as $item) {
                $this->_ordering[$item->getParentId()][] = $item->getId();
            }
        }
        return $this;
    }
    
    /**
     * Renders column
     *
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        if (!$this->_ordering || empty($this->_ordering) || !$this->getColumn()->getEnabled()) {
            return '';
        }
        $parentOrdering = isset($this->_ordering[$row->getParentId()]) ? $this->_ordering[$row->getParentId()] : array();
        $orderKey = array_search($row->getId(), $parentOrdering);
        
        $grid = $this->getColumn()->getGrid();
        /* @var $grid Mage_Adminhtml_Block_Widget_Grid */
        
        $html = '';
        // Add Order Up icon
        $html .= '<span>';
        if (isset($parentOrdering[$orderKey - 1])) {
            $orderUp = $this->getColumn()->getOrderUp();
            $params = array($this->getColumn()->getOrderField() => $this->_getValue($row));
            if(isset($orderUp['params'])) {
                $params = array_merge($orderUp['params'], $params);
            }
            $html .= '<a title="'.Mage::helper('all4coding_core')->__('Move Up').'" onclick="return all4coding.core.moveItem('.$grid->getJsObjectName().', \''.$this->getUrl($orderUp['base'], $params).'\')" href="javascript:void(0);">';
            $html .= '<span class="uparrow"><span class="text">'.Mage::helper('all4coding_core')->__('Move Up').'</span></span>';
            $html .= '</a>';
        } else {
            $html .= '&nbsp;';
        }
        $html .= '</span>';
        
        // Add Order Down icon
        $html .= '<span>';
        if (isset($parentOrdering[$orderKey + 1])) {
            $orderDown = $this->getColumn()->getOrderDown();
            $params = array($this->getColumn()->getOrderField() => $this->_getValue($row));
            if(isset($orderDown['params'])) {
                $params = array_merge($orderDown['params'], $params);
            }
            $html .= '<a class="" title="'.Mage::helper('all4coding_core')->__('Move Down').'" onclick="return all4coding.core.moveItem('.$grid->getJsObjectName().', \''.$this->getUrl($orderDown['base'], $params).'\')" href="javascript:void(0);">';
            $html .= '<span class="downarrow"><span class="text">'.Mage::helper('all4coding_core')->__('Move Down').'</span></span>';
            $html .= '</a>';
        } else {
            $html .= '&nbsp;';
        }
        $html .= '</span>';
        
        return $html;
    }
    
    public function renderCss()
    {
        return parent::renderCss().' a4c_order';
    }
}