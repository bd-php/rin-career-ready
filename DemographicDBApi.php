<?php

include_once("Config.php");
$conn = new mysqli($Server, $UserID, $Password, $Database);

$educational_qualification = $_REQUEST['educational_qualification'];
$gender = $_REQUEST['gender'];
$age = $_REQUEST['age'];
$postalCode = $_REQUEST['postalCode'];
$Aptitude_question_pass = $_REQUEST['Aptitude_question_pass'];
$operation = $_REQUEST['operation']; // show data or export to csv

$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$endDate = $endDate . " 23:59:59";

// configure from front end
$ALL = 'all';
$other = 'others';
$pass = 'pass';
$failed = 'failed';


if ($educational_qualification == $ALL) {
    $educational_qualification_qry = ' 1=1 ';
} else
    $educational_qualification_qry = ' educational_qualification=' . "'$educational_qualification' ";

if ($gender == $ALL) {
    $gender_qry = ' AND 1=1 ';
} else
    $gender_qry = ' AND gender=' . "'$gender' ";

if ($age == $ALL) {
    $age_qry = ' AND 1=1 ';
} else
    $age_qry = ' AND age=' . "'$age' ";

if ($postalCode == $ALL) {
    $postalCode_qry = ' AND 1=1 ';
} else if ($postalCode == $other) {
    $postalCode_qry = ' AND postalCode NOT IN (SELECT PostalCode FROM tbl_postal_code) ';
} else
    $postalCode_qry = ' AND postalCode=' . "'$postalCode'";

if ($Aptitude_question_pass == $ALL) {
    $Aptitude_question_pass_qry = ' AND 1=1 ';
} elseif ($Aptitude_question_pass == $pass)

    $Aptitude_question_pass_qry = ' AND rq.Correct_answer=ra.id_ans ';

elseif ($Aptitude_question_pass == $failed)

    $Aptitude_question_pass_qry = ' AND rq.Correct_answer<>ra.id_ans ';
elseif ($Aptitude_question_pass == 2) {
    $Aptitude_question_pass_qry = "AND (ra.QuestionCategory='Apt1'  AND rq.Correct_answer=ra.id_ans)
    AND (ra.QuestionCategory='Apt2'  AND rq.Correct_answer<>ra.id_ans)";
} elseif ($Aptitude_question_pass == 3) {
    $Aptitude_question_pass_qry = "AND (ra.QuestionCategory='Apt1'  AND rq.Correct_answer<>ra.id_ans)
    AND (ra.QuestionCategory='Apt2'  AND rq.Correct_answer=ra.id_ans)";
}


if ($operation == 'show') {

    $count_total = 0;
    $sql = "SELECT 	count(*) as count_total	FROM 	tbl_users  AS t INNER JOIN tbl_reg_answers ra ON ra.MSISDN=t.MSISDN	INNER JOIN tbl_reg_questions rq ON rq.ID=ra.id_ques WHERE  ";
    $sql .= $educational_qualification_qry . $gender_qry . $age_qry . $postalCode_qry . $Aptitude_question_pass_qry;

    // For Date Range
    $sql = $sql . "AND registration_date BETWEEN " . "'2006:00:00'  AND  '$endDate'";

    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $count_total = $row["count_total"];
    }
    echo $count_total / 2;

    mysqli_free_result($result);
    mysqli_close($conn);

} else if ($operation == 'export') {
    $sql = "SELECT 	t.MSISDN, t.registration_date, t.educational_qualification,
        t.gender, t.age, t.postalCode
	    FROM 	tbl_users  AS t INNER JOIN tbl_reg_answers ra 
	    ON ra.MSISDN=t.MSISDN	INNER JOIN tbl_reg_questions rq 
	    ON rq.ID=ra.id_ques WHERE  ";

    $sql .= $educational_qualification_qry . $gender_qry . $age_qry . $postalCode_qry . $Aptitude_question_pass_qry;

    // For Date Range
    $sql = $sql . "AND registration_date BETWEEN " . "'$startDate'  AND  '$endDate'";

    createCSV($sql, $conn);
} else if ($operation == 'exportUserCompletion') {
    $sql = "SELECT @n := @n + 1 NO, 
            msisdn  FROM blocklist, (SELECT @n := 0) m
            WHERE lastUpdate >= '2006-00-00' AND
            lastUpdate <= '$endDate' ORDER BY lastUpdate ";
    createCSV($sql, $conn);
} else if ($operation == 'regionWiseUserCount') {
    echo "<table class=\"table table-bordered table-inverse\">";
    echo "<tr>";
    echo "<td>Division</td>";
    echo "<td>Users</td>";
    echo "</tr>";

    $divisions = array("Dhaka", "Chittagong", "Rajshahi", "Khulna", "Sylhet", "Barisal", "Rangpur");

    $divisionTotal = 0;

    foreach ($divisions as $division) {
        $sql = "SELECT COUNT(*) FROM (SELECT tbl_users.`ID`, tbl_users.`MSISDN`, 
        tbl_users.`postalCode`, tbl_postal_code_copy.`Division`
        FROM tbl_users 
        INNER JOIN tbl_postal_code_copy 
        ON tbl_users.`postalCode` = tbl_postal_code_copy.`PostalCode` 
        WHERE Division = '$division' AND lastupdate BETWEEN '2006:00:00' AND '$endDate'
        )a";

        $res = $conn->query($sql);
        $row = $res->fetch_row();
        $count = $row[0];
        $divisionTotal += $count;
        echo "<tr>";
        echo "<td>$division</td>";
        echo "<td>$count</td>";
        echo "</tr>";
    }

    $sql = "SELECT COUNT(*) FROM tbl_users";

    $res = $conn->query($sql);
    $row = $res->fetch_row();
    $allTotal = $row[0];

    $others = $allTotal - $divisionTotal;

    echo "<tr>";
    echo "<td>Others</td>";
    echo "<td>$others</td>";
    echo "</tr>";


    echo "</table>";
}


function createCSV($sql, $conn)
{

    $result = $conn->query($sql);
    $filename = "data_" . date("Y-m-d_H-i", time());

    if (mysqli_num_rows($result) > 0) {
        ## CSV Header
        header("Content-type:text/octect-stream");
        header("Content-Disposition:attachment;filename=$filename.csv");

        ## Header
        $i = 0;
        while ($field_info = mysqli_fetch_field($result)) {
            $header[$i] = $field_info->name;
            print $header[$i] . ",";
            $i++;
        }

        print "\n";

        ## Body
        while ($row = $result->fetch_assoc()) {
            print '"' . stripslashes(implode('","', $row)) . "\"\n";
        } // While Loop Ends
    } else {
        print "<script>
			alert ('Sorry, no data found to be processed. ');
			window.close();
		</script>";
    }// If Loop Ends

    mysqli_free_result($result);
    mysqli_close($conn);
}