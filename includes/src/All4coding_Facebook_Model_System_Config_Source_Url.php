<?php
class All4coding_Facebook_Model_System_Config_Source_Url
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'currentpage', 'label'=>Mage::helper('all4coding_facebook')->__('Current site page')),
            array('value'=>'fanpage', 'label'=>Mage::helper('all4coding_facebook')->__('Facebook fanpage')),
        );
    }
}