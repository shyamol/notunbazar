<?php
class Magazento_Facebook_Adminhtml_Model_System_Config_Source_Font
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('facebook')->__('')),
            array('value'=>'arial', 'label'=>Mage::helper('facebook')->__('arial')),
            array('value'=>'lucida grande', 'label'=>Mage::helper('facebook')->__('lucida grande')),
            array('value'=>'segoe ui', 'label'=>Mage::helper('facebook')->__('segoe ui')),
            array('value'=>'tahoma', 'label'=>Mage::helper('facebook')->__('tahoma')),
            array('value'=>'trebuchet ms', 'label'=>Mage::helper('facebook')->__('trebuchet ms')),
            array('value'=>'verdana', 'label'=>Mage::helper('facebook')->__('verdana'))
        );
    }
}