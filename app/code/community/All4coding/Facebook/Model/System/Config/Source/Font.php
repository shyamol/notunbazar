<?php
class All4coding_Facebook_Model_System_Config_Source_Font
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('all4coding_facebook')->__('')),
            array('value'=>'arial', 'label'=>Mage::helper('all4coding_facebook')->__('arial')),
            array('value'=>'lucida grande', 'label'=>Mage::helper('all4coding_facebook')->__('lucida grande')),
            array('value'=>'segoe ui', 'label'=>Mage::helper('all4coding_facebook')->__('segoe ui')),
            array('value'=>'tahoma', 'label'=>Mage::helper('all4coding_facebook')->__('tahoma')),
            array('value'=>'trebuchet ms', 'label'=>Mage::helper('all4coding_facebook')->__('trebuchet ms')),
            array('value'=>'verdana', 'label'=>Mage::helper('all4coding_facebook')->__('verdana'))
        );
    }
}