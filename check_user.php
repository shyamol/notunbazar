<?php
require_once('app/Mage.php');
Mage::app();
$connection = Mage::getSingleton('core/resource')->getConnection('core_read');

//if (isset($_POST['user'])) {//If a username has been submitted
    //$username = mysql_real_escape_string($_POST['user']);
    $username = $_POST['user'];
    $eav_attributeTable = Mage::getSingleton('core/resource')->getTableName('eav_attribute'); //table name
    $query = "SELECT attribute_id FROM " . $eav_attributeTable . " WHERE attribute_code = 'nickname'";
    $attribute_id = $connection->fetchOne($query);
    $customer_entity_varcharTable = Mage::getSingleton('core/resource')->getTableName('customer_entity_varchar'); //table name
    $query = "SELECT count(value_id) FROM " . $customer_entity_varcharTable . " WHERE attribute_id = '$attribute_id' and value = '$username'" ;
    $rows = $connection->fetchOne($query); //fetchRow($sql), fetchOne($sql),...
    if(intval($rows) === 0){
        ?>
        <span class="available" align="absmiddle" style="margin-left: 20px"> <font color="Green"> Available</font></span>
        <?php
        //add this image to the span with id "#availability_status"
//echo '1'; //Not Available
    } else {
        ?>
        <span class="not_available" align="absmiddle" style="margin-left: 20px"> <font color="red">Not Available </font> </span>
        <?php

//echo '0';  // Username is available
    }
//}
?>