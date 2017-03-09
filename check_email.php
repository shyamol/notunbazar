<?php
//$mageFilename = 'http://dev.notunbazar.com/app/Mage.php';
require_once('app/Mage.php');
//Mage::setIsDeveloperMode(true);
//ini_set('display_errors', 1);
//umask(0);
Mage::app();
$connection = Mage::getSingleton('core/resource')->getConnection('core_read');

if (isset($_POST['email'])) {//If a username has been submitted
    //$useremail = mysql_real_escape_string($_POST['email']);
    $useremail = $_POST['email'];
    $customerTable = Mage::getSingleton('core/resource')->getTableName('customer_entity'); //table name
    $query = "SELECT count(email) FROM " . $customerTable . " WHERE email = '$useremail'";
    //$sku = $db_read->fetchRow($query);
    $rows = $connection->fetchOne($query); //fetchRow($sql), fetchOne($sql),...
    if (intval($rows) === 0) {
        ?>
        <span class="available" align="absmiddle" style="margin-left: 20px"> <font color="Green"> Available</font></span>
        <?php
        //add this image to the span with id "#availability_status"
//echo '1'; //Not Available
    } else {
        ?>
        <span class="not_available" align="absmiddle" style="margin-left: 20px"> <font color="red"> Not Available </font> </span>
        <?php

//echo '0';  // Username is available
    }
}
?>