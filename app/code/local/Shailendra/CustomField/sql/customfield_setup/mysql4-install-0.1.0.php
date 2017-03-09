<?php

$installer = $this;

$installer->startSetup();

$this->addAttribute('customer', 'mobile', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => 'Mobile',
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'default' => null,
    'visible_on_front' => 1
));


if (version_compare(Mage::getVersion(), '1.6.0', '<=')) {
    $customer = Mage::getModel('customer/customer');
    $attrSetId = $customer->getResource()->getEntityType()->getDefaultAttributeSetId();
    $this->addAttributeToSet('customer', $attrSetId, 'General', 'mobile');
}

if (version_compare(Mage::getVersion(), '1.4.2', '>=')) {
    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'mobile')
            ->setData('used_in_forms', array('adminhtml_customer', 'customer_account_create', 'customer_account_edit', 'checkout_register'))
            ->save();
}

$installer->endSetup();