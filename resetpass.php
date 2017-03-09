<?php
$pass = "12345678";
$salt = "at";
echo md5($salt.$pass).":".$salt;
?>