<?php
class All4coding_Facebook_Model_System_Config_Source_Color
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'light', 'label'=>Mage::helper('all4coding_facebook')->__('Light')),
            array('value'=>'dark', 'label'=>Mage::helper('all4coding_facebook')->__('Dark'))
        );
    }
}
