<?php
class Magazento_Facebook_Adminhtml_Model_System_Config_Source_Layout
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'standard', 'label'=>Mage::helper('facebook')->__('standard')),
            array('value'=>'button_count', 'label'=>Mage::helper('facebook')->__('button_count')),
            array('value'=>'box_count', 'label'=>Mage::helper('facebook')->__('box_count')),
        );
    }
}
