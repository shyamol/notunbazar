<?php
class All4coding_Facebook_Model_System_Config_Source_Size
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'small', 'label'=>Mage::helper('all4coding_facebook')->__('small')),
            array('value'=>'large', 'label'=>Mage::helper('all4coding_facebook')->__('large'))
        );
    }
}
