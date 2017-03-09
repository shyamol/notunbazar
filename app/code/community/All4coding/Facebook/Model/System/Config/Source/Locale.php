<?php

class All4coding_Facebook_Model_System_Config_Source_Locale {

    public function toOptionArray() {
    	$options = array();
    	$options[] = array('value' => 'auto', 'label' => Mage::helper('all4coding_facebook')->__('Automatically'));
    	
        $fileStr = file_get_contents('http://www.facebook.com/translations/FacebookLocales.xml');
        $fileXml = simplexml_load_string($fileStr, 'Varien_Simplexml_Element');
        if (!$fileXml instanceof SimpleXMLElement) {
        	return $options;
        }
        foreach($fileXml->children() as $child) {
        	$options[] = array('value' => $child->codes->code->standard->representation, 'label' => $child->englishName);
        }
        
        return $options;
    }

}
