<?php
class All4coding_Facebook_Model_System_Config_Source_Layout
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'standard', 'label'=>Mage::helper('all4coding_facebook')->__('standard')),
            array('value'=>'button_count', 'label'=>Mage::helper('all4coding_facebook')->__('button_count')),
            array('value'=>'box_count', 'label'=>Mage::helper('all4coding_facebook')->__('box_count'))
        );
    }
}
