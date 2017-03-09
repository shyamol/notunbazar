<?php
class Nbazar_Promotion_Model_Indexer extends Mage_Index_Model_Indexer_Abstract
{
    const EVENT_MATCH_RESULT_KEY = 'promotion_match_result';
protected $_matchedEntities = array(
    'test_entity' => array(
        Mage_Index_Model_Event::TYPE_SAVE
    )

);

public function getName(){
return Mage::helper('promotion')->__('Profile url rewrite');
}

public function getDescription(){
return Mage::helper('promotion')->__('Profile url rewrite');
}

protected function _registerEvent(Mage_Index_Model_Event $event){
// custom register event
return $this;
}

protected function _processEvent(Mage_Index_Model_Event $event){
        Mage::getSingleton('checkout/session')->setData('url','6');
}

public function reindexAll(){
// reindex all data
}
}
?>