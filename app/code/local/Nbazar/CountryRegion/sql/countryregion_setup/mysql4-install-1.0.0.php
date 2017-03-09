<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table country_region(country_region_id int not null auto_increment, country_id int, state_id int, region_id int, primary key(country_region_id));
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 