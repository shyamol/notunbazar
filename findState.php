<?php
$xml = simplexml_load_file("app/etc/local.xml");
$db = $xml->global->resources->default_setup->connection->dbname;
$host = $xml->global->resources->default_setup->connection->host;
$user = $xml->global->resources->default_setup->connection->username;
$pass = $xml->global->resources->default_setup->connection->password;
$table_prefix = $xml->global->resources->db->table_prefix;

$country = intval($_GET['country']);
//$country = 4;
$con = mysql_connect($host, $user, $pass);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db($db);

//$query="SELECT id,statename FROM state WHERE countryid='$country'";
$query = "SELECT t1.state_id as id, t2.value as statename FROM " . $table_prefix . "country_region as t1 INNER JOIN " . $table_prefix . "eav_attribute_option_value as t2 where t1.country_id = " . $country . " and t2.store_id = 0 and t1.state_id = t2.option_id ORDER BY statename ASC";
$result = mysql_query($query);
?>
<select name="state_district" id="state_district" onchange="getCity(<?php echo $country ?>, this.value)" class="cate-se validate-select">
    <option>Select State</option>
    <?php while ($row = mysql_fetch_array($result)) { ?>
        <option value=<?php echo $row['id'] ?>><?php echo $row['statename'] ?></option>
    <?php } ?>
</select>
