<?php
$xml = simplexml_load_file("app/etc/local.xml");
$db = $xml->global->resources->default_setup->connection->dbname;
$host = $xml->global->resources->default_setup->connection->host;
$user = $xml->global->resources->default_setup->connection->username;
$pass = $xml->global->resources->default_setup->connection->password;
$table_prefix = $xml->global->resources->db->table_prefix;

$countryId = intval($_GET['country']);
$stateId = intval($_GET['state']);
$con = mysql_connect($host, $user, $pass);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db($db);
//$query="SELECT id,city FROM city WHERE countryid='$countryId' AND stateid='$stateId'";
$query = "SELECT t1.region_id as id, t2.value as regionname FROM " . $table_prefix . "country_region as t1 INNER JOIN " . $table_prefix . "eav_attribute_option_value as t2 where t1.country_id = " . $countryId . " and  t1.state_id = " . $stateId . " and t2.store_id = 0 and t1.region_id = t2.option_id group by id ORDER BY regionname ASC";
$result = mysql_query($query);
?>
<select name="region_city" id="region_city" class="cate-se validate-select">
    <option>Select City</option>
    <?php while ($row = mysql_fetch_array($result)) { ?>
        <option value=<?php echo $row['id'] ?>><?php echo $row['regionname'] ?></option>
    <?php } ?>
</select>
