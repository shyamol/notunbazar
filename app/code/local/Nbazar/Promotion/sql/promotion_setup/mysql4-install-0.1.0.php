<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table promotion(promotion_id int(11) NOT NULL AUTO_INCREMENT,  product_id int(10) DEFAULT NULL, status smallint(2) NOT NULL DEFAULT '0', adtype int(2) DEFAULT NULL, customer_id int(10) DEFAULT NULL, order_id int(10) DEFAULT NULL, order_status int(2) DEFAULT NULL, created_at timestamp NULL DEFAULT NULL, updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, active_from timestamp NULL DEFAULT NULL,  active_to timestamp NULL DEFAULT NULL, primary key(promotion_id));
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 