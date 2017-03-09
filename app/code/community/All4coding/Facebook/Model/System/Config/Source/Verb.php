<?php
class All4coding_Facebook_Model_System_Config_Source_Verb
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'like', 'label'=>Mage::helper('all4coding_facebook')->__('Like')),
            array('value'=>'recommend', 'label'=>Mage::helper('all4coding_facebook')->__('Recommend'))
        );
    }
}