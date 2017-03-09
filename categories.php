<?php
require_once 'app/Mage.php';
Mage::app();
$allCategories = Mage::getModel ( 'catalog/category' );
$categoryTree = $allCategories->getTreeModel();
$categoryTree->load();
$categoryIds = $categoryTree->getCollection()->getAllIds ();	
if ($categoryIds) {
	$outputFile = "var/importexport/categories-and-ids.csv";
	$write = fopen($outputFile, 'w');
	foreach ( $categoryIds as $categoryId ) {
		$data = array($allCategories->load($categoryId)->getName(), $categoryId);
		fputcsv($write, $data);
	}
}
fclose($write);
?>