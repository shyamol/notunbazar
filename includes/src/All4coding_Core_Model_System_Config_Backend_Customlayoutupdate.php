<?php
/**
 * Core System Config Backend CustomLayoutUpdate
 *
 * @category    All4coding
 * @package     All4coding_Core
 * @author      All For Coding <info@all4coding.com>
 */
class All4coding_Core_Model_System_Config_Backend_Customlayoutupdate extends Mage_Core_Model_Config_Data
{
    protected function _beforeSave()
    {
        $xml = $this->getValue();
        if ($xml) {
            /** @var $validator Mage_Adminhtml_Model_LayoutUpdate_Validator */
            $validator = Mage::getModel('adminhtml/layoutUpdate_validator');
            if (!$validator->isValid($xml)) {
                $messages = $validator->getMessages();
                //Add first message to exception
                $message = array_shift($messages);
                Mage::throwException($this->getFieldConfig()->label.': '.$message);
            }
        }
    }
}