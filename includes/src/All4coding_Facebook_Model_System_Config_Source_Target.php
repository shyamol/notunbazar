<?php
class All4coding_Facebook_Model_System_Config_Source_Target
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'_blank', 'label'=>Mage::helper('all4coding_facebook')->__('_blank')),
            array('value'=>'_top', 'label'=>Mage::helper('all4coding_facebook')->__('_top')),
            array('value'=>'_top', 'label'=>Mage::helper('all4coding_facebook')->__('_parent'))
        );
    }
}