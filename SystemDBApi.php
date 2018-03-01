<?php
/**
 * Created by IntelliJ IDEA.
 * User: smssd
 * Date: 8/28/2016
 * Time: 4:42 PM
 */

include_once("Config.php");

$option = $_REQUEST["option"];

$conn = new mysqli($Server, $UserID, $Password, $Database);

if ($option == "userCount") {
    $userStartDate = $_REQUEST["userStartDate"];
    $userStartTime = $_REQUEST["userStartTime"];
    $userEndDate = $_REQUEST["userEndDate"];
    $userEndTime = $_REQUEST["userEndTime"];

    $userStartDateTime = $userStartDate . " " . $userStartTime . ":00";
    $userEndDateTime = $userEndDate . " " . $userEndTime . ":00";

    $sql = "SELECT COUNT(*) FROM tbl_users WHERE MSISDN NOT IN  ( SELECT MSISDN FROM blocklist ) AND registration_date BETWEEN '$userStartDateTime' AND '$userEndDateTime' ";

    $result = $conn->query($sql);
    $row = $result->fetch_row();
    $callCount = $row[0];

    echo $callCount;

} else if ($option == "campaignCount") {
    $campaignStartDate = $_REQUEST["campaignStartDate"];
    $campaignStartTime = $_REQUEST["campaignStartTime"];
    $campaignEndDate = $_REQUEST["campaignEndDate"];
    $campaignEndTime = $_REQUEST["campaignEndTime"];

    $campaignStartDateTime = $campaignStartDate . " " . $campaignStartTime . ":00";
    $campaignDateTime = $campaignEndDate . " " . $campaignEndTime . ":00";

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

    echo $totalMinuteCompleted;

} else if ($option == "smsCount") {
    $smsStartDate = $_REQUEST["smsStartDate"];
    $smsStartTime = $_REQUEST["smsStartTime"];
    $smsEndDate = $_REQUEST["smsEndDate"];
    $smsEndTime = $_REQUEST["smsEndTime"];

    $smsStartDateTime = $smsStartDate . " " . $smsStartTime . ":00";
    $smsEndDateTime = $smsEndDate . " " . $smsEndTime . ":00";

    $sql = "SELECT COUNT(*) FROM tbl_miss_call_history WHERE date BETWEEN '$smsStartDateTime' AND '$smsEndDateTime' ";

    $result = $conn->query($sql);
    $row = $result->fetch_row();
    $callCount = $row[0];

    echo $callCount;
}