<?php
class Magazento_Facebook_Adminhtml_Model_System_Config_Source_Size
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'small', 'label'=>Mage::helper('facebook')->__('small')),
            array('value'=>'large', 'label'=>Mage::helper('facebook')->__('large')),
        );
    }
}
