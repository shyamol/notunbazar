<?php
/**
 * @category     Inchoo
 * @package     Inchoo Top Products
 * @author        Domagoj Potkoc, Inchoo Team <web@inchoo.net>
 * @modified    Mladen Lotar <mladen.lotar@surgeworks.com>, Vedran Subotic <vedran.subotic@surgeworks.com>
 */

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

$installer->updateAttribute('catalog_product', 'inchoo_top_product', 'is_global', '0');

$installer->endSetup();