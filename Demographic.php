<?php
session_start();
include_once 'Config.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

$user = $_SESSION['user'];

$sql = "select * from tbl_login where username = '$user'";

$stmt = $pdo->query($sql);
$row = $stmt->fetchObject();

?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RIN- Career Ready Academy</title>
    <link href="css/reset.css" rel="stylesheet" type="text/css">
    <link href="css/base.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
    <link href="css/jquery.datetimepicker.min.css" rel="stylesheet" type="text/css">
    <script src="js/jquery.datetimepicker.full.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"
            integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7"
            crossorigin="anonymous"></script>
</head>

<body>
<div class="section toparea">
    <div class="wrapper">
        <div class="columns branding">
            <div class="tagline"><img src="images/rin.png" width="80" height="65"></div>
        </div>
        <div class="columns user-area">
            <div class="logged-name" style="margin-top: 20px;">Welcome <?php echo $row->username; ?> <a
                    href="logout.php?logout">Logout</a></div>
        </div>
    </div>
</div>
<div class="main-nav">
    <ul id="accordion" class="accordion">
        <li class="default open">
            <div class="link"><a href="home.php">Dashboard</a></div>
        </li>
        <li>
            <div class="link"><a href="Demographic.php">Demographic</a></div>
        </li>
        <li>
            <div class="link"><a href="Campaign.php">Campaign</a></div>
        </li>
        <li>
            <div class="link"><a href="System.php">System</a></div>
        </li>
    </ul>
</div>
<div class="content-area">
    <h1>Demographic</h1>
    <div class="row mtop ">
        <div class="twelve columns card">
            <h2>Registered Users</h2>
            <div class="card-content">
                <div class="search-group">

                    <div class="two columns">
                        <input type="text" id="user-start-date" placeholder="Start Date">
                    </div>
                    <div class="two columns">
                        <input type="text" id="user-end-date" placeholder="End Date">
                    </div>

                    <div class="two columns">
                        <select id="education">
                            <option value="0">All Education Level</option>
                            <option value="1">School Pass</option>
                            <option value="2">University Graduate</option>
                            <option value="3">Others</option>
                        </select>
                    </div>
                    <div class="two columns">
                        <select id="gender">
                            <option value="0">All Gender</option>
                            <option value="1">Female</option>
                            <option value="2">Male</option>
                        </select>
                    </div>
                    <div class="two columns">
                        <select id="age">
                            <option value="0">All Age Group</option>
                            <option value="1">Below 18</option>
                            <option value="2">18 to 25</option>
                            <option value="3">Over 25</option>
                        </select>
                    </div>
                    <div class="two columns">
                        <select id="aptitude">
                            <option value="0">All Aptitude</option>
                            <option value="1">Q1 Passed & Q2 Passed</option>
                            <option value="2">Q1 Passed & Q2 Failed</option>
                            <option value="3">Q1 Failed & Q2 Passed</option>
                            <option value="4">Q1 Failed & Q2 Failed</option>
                        </select>
                    </div>
                    <div class="two columns" style="margin-top: 20px">
                        <input type="submit" value="Search" onclick="countUsers()">
                    </div>

                    <div class="two columns" style="margin-top: 20px">
                        <input type="submit" value="Export" onclick="exportCSV()">
                    </div>

                    <div class="clearfix"></div>
                    <div class="row search-result">
                        <h4>Total Count = <strong id="totalCount">0</strong></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mtop">
        <div class="twelve columns card">
            <h2>Course Completed</h2>
            <div class="card-content">
                <div class="search-group">
                    <div class="two columns">
                        <input type="text" id="completion-start-date" placeholder="Start Date">
                    </div>
                    <div class="two columns">
                        <input type="text" id="completion-end-date" placeholder="End Date">
                    </div>
                    <div class="two columns">
                        <input type="submit" value="Export" onclick="exportCSVUserCompletion()">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mtop">
        <div class="twelve columns card">
            <h2>Users Region</h2>
            <div class="card-content">
                <div class="search-group">
                    <div class="two columns">
                        <input type="text" id="region-start-date" placeholder="Start Date">
                    </div>
                    <div class="two columns">
                        <input type="text" id="region-end-date" placeholder="End Date">
                    </div>
                    <div class="two columns">
                        <input type="submit" value="Show" onclick="showUsers()">
                    </div>
                    <div class="two columns">
                        <h1>Total</h1>
                        <p id="divisionWiseUsers"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="section footer">
    <div class="wrapper">
        <div class="columns copyright">
            <p>RIN - Career Ready Academy | All Rights Reserved | 2016 | Powered by <span>SSD-Tech</span></p>
        </div>
    </div>
</div>
</body>

<script>

    $(function () {
        $('#user-start-date, #user-end-date, #region-start-date, #region-end-date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#completion-start-date, #completion-end-date').datepicker({dateFormat: 'yy-mm-dd'});
        $("#user-start-date").datepicker('setDate', '2016-09-16');
        $("#user-end-date").datepicker('setDate', new Date());
        $("#completion-start-date").datepicker('setDate', '2016-09-16');
        $("#completion-end-date").datepicker('setDate', new Date());
        $("#region-start-date").datepicker('setDate', '2016-09-16');
        $("#region-end-date").datepicker('setDate', new Date());
    });

    function countUsers() {

        var education = $("#education").val();

        if (education == 0) {
            education = "all";
        }
        var gender = $("#gender").val();

        if (gender == 0) {
            gender = "all";
        }

        var age = $("#age").val();

        if (age == 0) {
            age = "all";
        }

//        var region = $("#region option:selected").text();
//        if ($("#region option:selected").val() == 0) {
//            region = "all";
//        }

        var aptitude = $("#aptitude").val();
        if (aptitude == 0) {
            aptitude = "all";
        }
        if (aptitude == 1) {
            aptitude = "pass"
        }
        if (aptitude == 4) {
            aptitude = "failed";
        }

        var startDate = $("#user-start-date").val();
        var endDate = $("#user-end-date").val();

        //alert(education + gender + age + region + aptitude);

        var urlHit = "DemographicDBApi.php?operation=show&educational_qualification="
            + education + "&gender="
            + gender + "&age="
            + age + "&postalCode="
            + 'all' + "&Aptitude_question_pass="
            + aptitude + "&startDate="
            + startDate + "&endDate="
            + endDate;

        var count = $.ajax({
            url: urlHit,
            success: function (result) {
                $("#totalCount").html(result);
            }
        });
    }

    function exportCSV() {


        var education = $("#education").val();

        if (education == 0) {
            education = "all";
        }
        var gender = $("#gender").val();

        if (gender == 0) {
            gender = "all";
        }

        var age = $("#age").val();

        if (age == 0) {
            age = "all";
        }

//        var region = $("#region option:selected").text();
//        if ($("#region option:selected").val() == 0) {
//            region = "all";
//        }


        var aptitude = $("#aptitude").val();
        if (aptitude == 0) {
            aptitude = "all";
        }

        var startDate = $("#user-start-date").val();
        var endDate = $("#user-end-date").val();

        //alert(education + gender + age + region + aptitude);

        var operation = 'export';

        var urlHit = "DemographicDBApi.php?operation=" + operation
            + "&educational_qualification="
            + education + "&gender="
            + gender + "&age="
            + age + "&postalCode="
            + 'all' + "&Aptitude_question_pass="
            + aptitude + "&startDate="
            + startDate + "&endDate="
            + endDate;

        //alert(urlHit);
        // var urlHit = "http://localhost/rin/DemographicDBApi.php?operation=show&educational_qualification=all&gender=1&age=3&postalCode=all&Aptitude_question_pass=all";

        window.open(urlHit);
    }

    function exportCSVUserCompletion() {
        var startDate = $("#completion-start-date").val();
        var endDate = $("#completion-end-date").val();

        var operation = 'exportUserCompletion';

        var urlHit = "DemographicDBApi.php?operation=" + operation
            + "&startDate=" + startDate
            + "&endDate="
            + endDate;

        //alert(urlHit);
        // var urlHit = "http://localhost/rin/DemographicDBApi.php?operation=show&educational_qualification=all&gender=1&age=3&postalCode=all&Aptitude_question_pass=all";

        window.open(urlHit);

    }

    function showUsers() {
        var startDate = $("#region-start-date").val();
        var endDate = $("#region-end-date").val();

        var operation = 'regionWiseUserCount';
        var urlHit = "DemographicDBApi.php?operation=" + operation
            + "&startDate=" + startDate
            + "&endDate=" + endDate;

        var count = $.ajax({
            url: urlHit,
            success: function (result) {
                $("#divisionWiseUsers").html(result);
            }
        });
    }

</script>

</html>
