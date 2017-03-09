<?php
class Magazento_Facebook_Adminhtml_Model_System_Config_Source_Url
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'currentpage', 'label'=>Mage::helper('facebook')->__('Current site page')),
            array('value'=>'fanpage', 'label'=>Mage::helper('facebook')->__('Facebook fanpage')),
        );
    }
}