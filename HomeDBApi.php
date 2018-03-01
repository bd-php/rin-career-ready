<?php

include_once("Config.php");

$conn = new mysqli($Server, $UserID, $Password, $Database);

// Total Registered users
$sql = "select count(*) from tbl_users as total";
$result = $conn->query($sql);
$row = $result->fetch_row();
$totalRegistered = $row[0];

// Total lesson completed
$sql = "select count(*) from blocklist";
$result = $conn->query($sql);
$row = $result->fetch_row();
$totalLesson = $row[0];


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

echo json_encode(array("totalRegistered" => $totalRegistered,
    "totalLesson" => $totalLesson,
    "totalMinuteCompleted" => $totalMinuteCompleted));
