<?php

include_once("Config.php");

$conn = new mysqli('localhost', $UserID, $Password, $Database);

$sql = "SELECT COUNT(*) FROM (SELECT tbl_users.`ID`, tbl_users.`MSISDN`, tbl_users.`postalCode`, tbl_postal_code_copy.`Division` FROM tbl_users INNER JOIN tbl_postal_code_copy ON tbl_users.`postalCode` = tbl_postal_code_copy.`PostalCode` WHERE Division = 'Dhaka' AND lastupdate BETWEEN '2006:00:00' AND '2016-10-10 23:59:59' )a";
$result = $conn->query($sql);

$row = $result->fetch_row();
$postalCodeOthers = $row[0];

echo $postalCodeOthers;


