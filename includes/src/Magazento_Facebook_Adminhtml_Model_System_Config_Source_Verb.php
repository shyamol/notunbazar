<?php
class Magazento_Facebook_Adminhtml_Model_System_Config_Source_Verb
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'like', 'label'=>Mage::helper('facebook')->__('Like')),
            array('value'=>'recommend', 'label'=>Mage::helper('facebook')->__('Recommend'))
        );
    }
}