<?php
header('Access-Control-Allow-Origin: *');
include_once("Config.php");
$conn = new mysqli($Server, $UserID, $Password, $Database);

// Total Registered users
$sql = "select count(*) from tbl_users as total";
$result = $conn->query($sql);
$row = $result->fetch_row();
$totalRegistered = $row[0];

// Total Minute Calculation
$sql = "SELECT SUM(MINUTE(TIMEDIFF(lastUpdate,start_time))+1)
FROM tbl_user_info_history";
$result = $conn->query($sql);
$row = $result->fetch_row();
$tbl_user_info_history_time = $row[0];


$sql = "SELECT SUM(MINUTE(TIMEDIFF(lastUpdate,start_time))+1)
FROM tbl_users_history";
$result = $conn->query($sql);
$row = $result->fetch_row();
$tbl_users_history_time = $row[0];


$sql = "SELECT SUM(MINUTE(TIMEDIFF(lastUpdate,start_time))+1)
FROM tbl_reg_answers";
$result = $conn->query($sql);
$row = $result->fetch_row();
$tbl_reg_answers_time = $row[0];

$totalMinuteCompleted = $tbl_user_info_history_time
    + $tbl_users_history_time
    + $tbl_reg_answers_time;

$totalMinuteCompleted = round($totalMinuteCompleted / 100000) . ' LAKH';

echo json_encode(array("num_of_registration" => $totalRegistered,
    "total_minutes" => $totalMinuteCompleted));
