<?php

include_once("Config.php");

$conn = new mysqli($Server, $UserID, $Password, $Database);

$option = $_REQUEST['option'];

if ($option == 'callCount') {
    if ($_REQUEST['menu'] == 0) {
        $sql = "SELECT count(*) as countTotal FROM tbl_miss_call_history";
        $result = $conn->query($sql);
        $row = $result->fetch_row();
        $callCount = $row[0];
        echo $callCount;
    } else if ($_REQUEST['menu'] == 1) {
        $sql = "SELECT COUNT(DISTINCT MSISDN)  FROM tbl_miss_call_history;";
        $result = $conn->query($sql);
        $row = $result->fetch_row();
        $callCount = $row[0];
        echo $callCount;
    } else if ($_REQUEST['menu'] == 2) {
        $sql = "SELECT * FROM tbl_users";
        $result_tbl_users = $conn->query($sql);
        $missCallCountTotal = 0;
        $count = 0;
        while ($row_tbl_users = $result_tbl_users->fetch_assoc()) {
            $registration_date = $row_tbl_users['registration_date'];
            $msisdn = $row_tbl_users['MSISDN'];
            $msisdn = '0' . $msisdn;   // MySQL return row by omitting 0 at the beginning of a string.
            // so for comparision purpose we are again concating 0 at the beginning of string
            $count++;
            $sql = "SELECT COUNT(*) FROM tbl_miss_call_history WHERE msisdn = '$msisdn' AND DATE > '$registration_date'";

            $result_tbl_miss_call_history = $conn->query($sql);
            $row = $result_tbl_miss_call_history->fetch_row();
            $missCallCount = $row[0];
            $missCallCountTotal += $missCallCount;
        }
        echo $missCallCountTotal;
    }
} else if ($option == 'regCount') {
    if ($_REQUEST['menu'] == 0) {
        $sql = "SELECT COUNT(DISTINCT MSISDN)  FROM tbl_users";
        $result = $conn->query($sql);
        $row = $result->fetch_row();
        $callCount = $row[0];
        echo "<h1>" . $callCount . "</h1>";
    } else if ($_REQUEST['menu'] == 1) {

        // Total Unique Miss Call
        $sql = "SELECT COUNT(DISTINCT MSISDN) FROM tbl_miss_call_history";
        $result = $conn->query($sql);
        $row = $result->fetch_row();
        $totalUniqueMissCall = $row[0];

        // Total Registered
        $sql = "SELECT COUNT(DISTINCT MSISDN) FROM tbl_users";
        $result = $conn->query($sql);
        $row = $result->fetch_row();
        $totalRegistered = $row[0];


        $totalDropout = $totalUniqueMissCall - $totalRegistered;

        echo "<h1>" . $totalDropout . "</h1>";

    } else if ($_REQUEST['menu'] == 2) {
        $sql = "SELECT COUNT(*) AS drop_out_number, drop_out_stage FROM (
        SELECT CASE WHEN c.total_count =4 THEN 'postalCode'
                WHEN c.total_count =1 THEN 'educational_qualification'	
                WHEN c.total_count =2 THEN 'gender'
                WHEN c.total_count =3 THEN 'age'
                ELSE 'drop_aptitude'
              END AS drop_out_stage  
    ,c.* FROM(
    SELECT COUNT(*) AS total_count, msisdn, DATE(start_time), HOUR(start_time) FROM (
    SELECT h.* FROM tbl_users_history h LEFT JOIN tbl_users u ON  u.msisdn=h.msisdn   WHERE IFNULL(u.msisdn,'')='' AND h.registration_date IS NULL 
    ) b GROUP BY msisdn ,DATE(start_time),HOUR(start_time)
    ) c) d GROUP BY drop_out_stage";

        $result_drop_out_stage = $conn->query($sql);

        echo "<table class=\"table table-bordered table-inverse\">";
        echo "<tr>";
        echo "<td>Drop Out Stage</td>";
        echo "<td>Drop Out Percentage</td>";
        echo "</tr>";

        // Total Unique Miss Call
        $sql = "SELECT COUNT(DISTINCT MSISDN) FROM tbl_miss_call_history";
        $result = $conn->query($sql);
        $row = $result->fetch_row();
        $totalUniqueMissCall = $row[0];

        // Total Given Consent
        $sql = "SELECT COUNT(DISTINCT MSISDN) FROM tbl_users_history";
        $result = $conn->query($sql);
        $row = $result->fetch_row();
        $totalUsersGivenConsent = $row[0];

        $drop_out_welcome_consent = $totalUniqueMissCall - $totalUsersGivenConsent;

        $totalCountForDivide = $drop_out_welcome_consent;

        while ($totalCount = $result_drop_out_stage->fetch_assoc()) {
            $totalCountForDivide = $totalCountForDivide + $totalCount['drop_out_number'];
        }

        mysqli_data_seek($result_drop_out_stage, 0);

        echo "<tr>";
        echo "<td>" . "Welcome" . "</td>";
        echo "<td>" . round(($drop_out_welcome_consent / $totalCountForDivide) * 100, 2) . " %</td>";
        echo "</tr>";

        while ($row_drop_out_stage = $result_drop_out_stage->fetch_assoc()) {
            $drop_out_number = $row_drop_out_stage['drop_out_number'];
            $drop_out_stage = $row_drop_out_stage['drop_out_stage'];
            if ($drop_out_stage == "age") {
                $drop_out_stage = "Age";
            } else if ($drop_out_stage == "educational_qualification") {
                $drop_out_stage = "Educational Qualification";
            } else if ($drop_out_stage == "gender") {
                $drop_out_stage = "Gender";
            } else if ($drop_out_stage == "postalCode") {
                $drop_out_stage = "Postal Code";
            } else if ($drop_out_stage = "drop_aptitude") {
                $drop_out_stage = "Drop Out aptitude";
            }
            if ($drop_out_stage != NULL) {
                echo "<tr>";
                echo "<td>" . $drop_out_stage . "</td>";
                echo "<td>" . round(($drop_out_number / $totalCountForDivide) * 100, 4) . " %</td>";
                echo "</tr>";
            }
        }
        echo "</table>";
    }
} else if ($option == 'moduleCount') {
    if ($_REQUEST['menu'] == 0) {   // Count of Each Module Completed
        $sql = "SELECT 'Registration' AS unit,(SELECT COUNT(*) FROM `tbl_users`) AS count_c

UNION ALL
SELECT 'Unit 1' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=1 AND lesson_info=5 AND question_info=6) AS count_c
UNION ALL
SELECT 'Unit 2' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=2 AND lesson_info=5 AND question_info=6) AS count_c
UNION ALL
SELECT 'Unit 3' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=3 AND lesson_info=5 AND question_info=6) AS count_c
UNION ALL
SELECT 'Unit 4' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=4 AND lesson_info=5 AND question_info=6) AS count_c
UNION ALL
SELECT 'Unit 5' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=5 AND lesson_info=5 AND question_info=6) AS count_c
UNION ALL
SELECT 'Unit 6' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=6 AND lesson_info=5 AND question_info=6) AS count_c
UNION ALL
SELECT 'Unit 7' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=7 AND lesson_info=5 AND question_info=6) AS count_c
UNION ALL
SELECT 'Unit 8' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=8 AND lesson_info=5 AND question_info=6) AS count_c
UNION ALL
SELECT 'Unit 9' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=9 AND lesson_info=5 AND question_info=6) AS count_c
UNION ALL
SELECT 'Unit 10' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=10 AND lesson_info=5 AND question_info=6) AS count_c
UNION ALL
SELECT 'Unit 11' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=11 AND lesson_info=5 AND question_info=6) AS count_c

UNION ALL
SELECT 'Unit 12' AS unit,(SELECT COUNT(*) FROM `blocklist`) AS count_c";

        $result = $conn->query($sql);

        echo "<table class=\"table table-bordered table-inverse\">";
        echo "<tr>";
        echo "<td>Unit Number</td>";
        echo "<td>User Count</td>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            $unit = $row['unit'];
            $count_c = $row['count_c'];

            echo "<tr>";
            echo "<td>" . $unit . "</td>";
            echo "<td>" . $count_c . "</td>";
            echo "</tr>";
        }
        echo "</table>";

    } else if ($_REQUEST['menu'] == 1) { // Count of Each Module Started
        $sql = "SELECT 'Registration' AS unit,(SELECT COUNT(*) FROM `tbl_users`) AS count_c

UNION ALL
SELECT 'Unit 1' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=1) AS count_c
UNION ALL
SELECT 'Unit 2' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=2) AS count_c
UNION ALL
SELECT 'Unit 3' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=3) AS count_c
UNION ALL
SELECT 'Unit 4' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=4) AS count_c
UNION ALL
SELECT 'Unit 5' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=5) AS count_c
UNION ALL
SELECT 'Unit 6' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=6) AS count_c
UNION ALL
SELECT 'Unit 7' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=7) AS count_c
UNION ALL
SELECT 'Unit 8' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=8) AS count_c
UNION ALL
SELECT 'Unit 9' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=9) AS count_c
UNION ALL
SELECT 'Unit 10' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=10) AS count_c
UNION ALL
SELECT 'Unit 11' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=11) AS count_c

UNION ALL
SELECT 'Unit 12' AS unit,(SELECT COUNT(*) FROM `blocklist`) AS count_c";

        $result = $conn->query($sql);

        echo "<table class=\"table table-bordered table-inverse\">";
        echo "<tr>";
        echo "<td>Unit Number</td>";
        echo "<td>User Count</td>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            $unit = $row['unit'];
            $count_c = $row['count_c'];

            echo "<tr>";
            echo "<td>" . $unit . "</td>";
            echo "<td>" . $count_c . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else if ($_REQUEST['menu'] == 2) {

        $sql = "select count(*) from blocklist";
        $result = $conn->query($sql);

        $row = $result->fetch_row();
        $total_completed = $row[0];

        echo "<h1>" . $total_completed . "</h1>";

    } else if ($_REQUEST['menu'] == 3) { // Count of Repeat Callers After Registration

        $sql = "SELECT *  FROM blocklist";
        $result_blocklist = $conn->query($sql);
        $repeatCallersCount = 0;
        while ($row_blocklist = $result_blocklist->fetch_assoc()) {
            $lastUpdate_blocklist = $row_blocklist['lastUpdate'];
            $msisdn = $row_blocklist['MSISDN'];
            $msisdn = '0' . $msisdn;   // MySQL return row by omitting 0 at the beginning of a string.
            // so for comparision purpose we are again concating 0 at the beginning of string

            $sql = "SELECT COUNT(*) FROM tbl_miss_call_history WHERE msisdn = '$msisdn' AND date > '$lastUpdate_blocklist'";

            $result_tbl_miss_call_history = $conn->query($sql);
            $row = $result_tbl_miss_call_history->fetch_row();
            $missCallCount = $row[0];
            $repeatCallersCount += $missCallCount;
        }
        echo "<h1>" . $repeatCallersCount . "</h1>";


    } else if ($_REQUEST['menu'] == 4) {

        $sql = "SELECT 'Registration' AS unit,(SELECT COUNT(*) FROM (SELECT tbl_users.`MSISDN` AS regMSISDN, tbl_user_info.`MSISDN` AS moduleMSISDN FROM tbl_users LEFT JOIN tbl_user_info ON tbl_users.`MSISDN` = tbl_user_info.`MSISDN` AND tbl_user_info.`MSISDN` IS NULL) b ) AS count_c

UNION ALL
SELECT 'Unit 1' AS unit, ( SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=1 AND lesson_info >= 1 AND question_info < 6 AND lastUpdate + 7 < NOW() ) AS count_c
UNION ALL
SELECT 'Unit 2' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=2 AND lesson_info >= 1 AND question_info < 6 AND lastUpdate + 7 < NOW() ) AS count_c
UNION ALL
SELECT 'Unit 3' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=3 AND lesson_info >= 1 AND question_info < 6 AND lastUpdate + 7 < NOW() ) AS count_c
UNION ALL
SELECT 'Unit 4' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=4 AND lesson_info >= 1 AND question_info < 6 AND lastUpdate + 7 < NOW() ) AS count_c
UNION ALL
SELECT 'Unit 5' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=5 AND lesson_info >= 1 AND question_info < 6 AND lastUpdate + 7 < NOW() ) AS count_c
UNION ALL
SELECT 'Unit 6' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=6 AND lesson_info >= 1 AND question_info < 6 AND lastUpdate + 7 < NOW() ) AS count_c
UNION ALL
SELECT 'Unit 7' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=7 AND lesson_info >= 1  AND question_info < 6 AND lastUpdate + 7 < NOW() ) AS count_c
UNION ALL
SELECT 'Unit 8' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=8 AND lesson_info >= 1  AND question_info < 6 AND lastUpdate + 7 < NOW() ) AS count_c
UNION ALL
SELECT 'Unit 9' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=9 AND lesson_info >= 1  AND question_info < 6 AND lastUpdate + 7 < NOW() ) AS count_c
UNION ALL
SELECT 'Unit 10' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=10 AND lesson_info >= 1  AND question_info < 6 AND lastUpdate + 7 < NOW() ) AS count_c
UNION ALL
SELECT 'Unit 11' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=11 AND lesson_info >= 1  AND question_info < 6 AND lastUpdate + 7 < NOW() ) AS count_c

UNION ALL
SELECT 'Unit 12' AS unit,(SELECT COUNT(*) FROM `blocklist`) AS count_c";

        $result = $conn->query($sql);

        echo "<table class=\"table table-bordered table-inverse\">";
        echo "<tr>";
        echo "<td>Unit Number</td>";
        echo "<td>User Count</td>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            $unit = $row['unit'];
            $count_c = $row['count_c'];
            echo "<tr>";
            echo "<td>" . $unit . "</td>";
            echo "<td>" . $count_c . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else if ($_REQUEST['menu'] == 5) {
        $sql = "SELECT 'Registration' AS unit,(SELECT COUNT(*) FROM `tbl_users`) AS count_c

UNION ALL
SELECT 'Unit 1' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=1 AND lesson_info >= 1 AND question_info <= 6) AS count_c
UNION ALL
SELECT 'Unit 2' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=2 AND lesson_info >= 1 AND question_info <= 6) AS count_c
UNION ALL
SELECT 'Unit 3' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=3 AND lesson_info >= 1 AND question_info <= 6) AS count_c
UNION ALL
SELECT 'Unit 4' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=4 AND lesson_info >= 1 AND question_info <= 6) AS count_c
UNION ALL
SELECT 'Unit 5' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=5 AND lesson_info >= 1 AND question_info <= 6) AS count_c
UNION ALL
SELECT 'Unit 6' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=6 AND lesson_info >= 1 AND question_info <= 6) AS count_c
UNION ALL
SELECT 'Unit 7' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=7 AND lesson_info >= 1  AND question_info <= 6) AS count_c
UNION ALL
SELECT 'Unit 8' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=8 AND lesson_info >= 1  AND question_info <= 6) AS count_c
UNION ALL
SELECT 'Unit 9' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=9 AND lesson_info >= 1  AND question_info <= 6) AS count_c
UNION ALL
SELECT 'Unit 10' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=10 AND lesson_info >= 1  AND question_info <= 6) AS count_c
UNION ALL
SELECT 'Unit 11' AS unit, (SELECT COUNT(*) FROM `tbl_user_info` WHERE unit_info=11 AND lesson_info >= 1  AND question_info <= 6) AS count_c

UNION ALL
SELECT 'Unit 12' AS unit,(SELECT COUNT(*) FROM `blocklist`) AS count_c";

        $result = $conn->query($sql);
        $total = 0;
        while ($row = $result->fetch_assoc()) {
            $total += $row['count_c'];
        }

        echo $total;

        // Total Unique Miss Call (As they are on registration process)
        $sql = "SELECT COUNT(DISTINCT MSISDN) FROM tbl_miss_call_history";
        $result = $conn->query($sql);
        $row = $result->fetch_row();
        $totalUniqueMissCall = $row[0];

        echo "<h1>" . round(($total / $totalUniqueMissCall) * 100, 2) . " % </h1>";

    } else if ($_REQUEST['menu'] == 6) { // days taken to complete the course
        $sql = "SELECT b.msisdn,DATEDIFF(c.lastUpdate, b.start_time) AS diff FROM  (SELECT MSISDN, MIN(start_time) AS start_time 
	FROM tbl_user_info GROUP BY MSISDN) b INNER JOIN 
(SELECT  MSISDN,lastUpdate FROM blocklist) c ON c.MSISDN = b.MSISDN";

        $result = $conn->query($sql);

        $msisdnTotal = 0;
        $timeTotal = 0;
        while ($row = $result->fetch_assoc()) {
            $msisdnTotal++;
            $timeTotal += $row['diff'];
        }

        if ($msisdnTotal != 0) {
            echo "<h1>" . round($timeTotal / $msisdnTotal) . "</h1>";
        } else {
            echo "<h1>" . "0" . "</h1>";
        }

    } else if ($_REQUEST['menu'] == 7) { // Average Number of miss call to complete
        // modules completionm time average
        $sql = "CALL `rin_career_ready_academy`.`countMissCallEachModuleCompletion`";
        $result = $conn->query($sql);

        echo "<table class=\"table table-bordered table-inverse\">";
        echo "<tr>";
        echo "<td>Unit Number</td>";
        echo "<td>Miss Call</td>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            $unit = $row['unit'];
            $avgMissCall = $row['avgMissCall'];
            echo "<tr>";
            echo "<td>" . $unit . "</td>";
            echo "<td>" . round($avgMissCall) . "</td>";
            echo "</tr>";
        }
        echo "</table>";

    } else if ($_REQUEST['menu'] == 8) {
        echo "<script>alert</script>";
    }
} else if ($option == 'regionCount') {
    if ($_REQUEST['menu'] == 'all') { // All Region

        $sql = "SELECT 	COUNT(*) AS count_total	FROM 	
                tbl_users";
        $result = $conn->query($sql);

        $row = $result->fetch_row();
        $postalCodeOthers = $row[0];

        echo "<h1>" . $postalCodeOthers . "</h1>";

    } else if ($_REQUEST['menu'] == 'others') { // Others
        $sql = "SELECT 	COUNT(*) AS count_total	FROM 	
                tbl_users WHERE postalCode NOT IN (SELECT postalCode 
                FROM tbl_postal_code)";
        $result = $conn->query($sql);

        $row = $result->fetch_row();
        $postalCodeOthers = $row[0];

        echo "<h1>" . $postalCodeOthers . "</h1>";

    } else { // All Region
        $postalCode = $_REQUEST['menu'];

        $sql = "SELECT 	COUNT(*) AS count_total	FROM 	
                tbl_users WHERE postalCode=" . $postalCode;
        $result = $conn->query($sql);

        $row = $result->fetch_row();
        $postalCodeOthers = $row[0];

        echo "<h1>" . $postalCodeOthers . "</h1>";

    }
}