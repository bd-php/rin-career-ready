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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
    <link href="css/jquery.datetimepicker.min.css" rel="stylesheet" type="text/css">
    <script src="js/jquery.datetimepicker.full.min.js"></script>
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
    <h1>System</h1>
    <div class="row">
        <div class="twelve columns card">
            <div class="card-content">
                <div class="search-group">
                    <h5>No for Active User</h5>
                    <div class="row">
                        <div class="two columns">
                            <input type="text" id="user-start-date" placeholder="Start Date">
                        </div>
                        <div class="two columns">
                            <input type="text" id="user-start-time" placeholder="Start Time">
                        </div>
                        <div class="two columns">
                            <input type="text" id="user-end-date" placeholder="End Date">
                        </div>
                        <div class="two columns">
                            <input type="text" id="user-end-time" placeholder="End Time">
                        </div>
                        <div class="two columns">
                            <input type="submit" value="Search" onclick="activeUsers()">
                        </div>

                        <div class="row search-result">
                            <h4>Total = <strong id="activeUser">0</strong></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mtop">
        <div class="twelve columns card">
            <div class="card-content">
                <div class="search-group">
                    <h5>Total Minute of Campaign</h5>
                    <div class="row">
                        <div class="two columns">
                            <input type="text" id="campaign-start-date" placeholder="Start Date">
                        </div>
                        <div class="two columns">
                            <input type="text" id="campaign-start-time" placeholder="Start Time">
                        </div>
                        <div class="two columns">
                            <input type="text" id="campaign-end-date" placeholder="End Date">
                        </div>
                        <div class="two columns">
                            <input type="text" id="campaign-end-time" placeholder="End Time">
                        </div>
                        <div class="two columns">
                            <input type="submit" value="Search" onclick="totalCampaign()">
                        </div>
                        <div class="row search-result">
                            <h4>Total = <strong id="activeCampaign">0</strong></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mtop">
        <div class="twelve columns card">
            <div class="card-content">
                <div class="search-group">
                    <h5>Total Miss Call Sent</h5>
                    <div class="row">
                        <div class="two columns">
                            <input type="text" id="sms-start-date" placeholder="Start Date">
                        </div>
                        <div class="two columns">
                            <input type="text" id="sms-start-time" placeholder="Start Time">
                        </div>
                        <div class="two columns">
                            <input type="text" id="sms-end-date" placeholder="End Date">
                        </div>
                        <div class="two columns">
                            <input type="text" id="sms-end-time" placeholder="End Time">
                        </div>
                        <div class="two columns">
                            <input type="submit" value="Search" onclick="totalSmsSent()">
                        </div>
                        <div class="row search-result">
                            <h4>Total = <strong id="activeSms">0</strong></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="section footer">
    <div class="wrapper">
        <div class="columns copyright">
            <p>RIN- Career Ready Academy | All Rights Reserved | 2016 | Powered by <span>SSD-Tech</span></p>
        </div>
    </div>
</div>
<script>

    jQuery('#user-start-time, #user-end-time, #campaign-start-time, #campaign-end-time, #sms-start-time, #sms-end-time').datetimepicker({
        datepicker: false,
        format: 'H:i'
    });


    $(function () {

        $('#user-start-date, #user-end-date, #campaign-start-date, #campaign-end-date, #sms-start-date, #sms-end-date').datepicker({dateFormat: 'yy-mm-dd'});

        $("#user-end-date, #campaign-end-date, #sms-end-date").datepicker('setDate', new Date());

        $("#user-start-date, #campaign-start-date, #sms-start-date").datepicker('setDate', '2016-09-16');

        $("#user-start-time, #campaign-start-time, #sms-start-time").val("00:00");

        $("#user-end-time, #campaign-end-time, #sms-end-time").val("23:00");

    });


    function activeUsers() {
        var userStartDate = $("#user-start-date").val();
        var userEndDate = $("#user-end-date").val();
        var userStartTime = $("#user-start-time").val();
        var userEndTime = $("#user-end-time").val();

        var urlHit = "SystemDBApi.php?option=userCount&userStartDate=" + userStartDate
            + "&userStartTime=" + userStartTime
            + "&userEndDate=" + userEndDate
            + "&userEndTime=" + userEndTime;

        //alert(urlHit);

        var count = $.ajax({
            url: urlHit,
            success: function (result) {
                $("#activeUser").html(result);
                //alert(result);
            }
        });
    }

    function totalCampaign() {
        var campaignStartDate = $("#campaign-start-date").val();
        var campaignStartTime = $("#campaign-start-time").val();
        var campaignEndDate = $("#campaign-end-date").val();
        var campaignEndTime = $("#campaign-end-time").val();


        var urlHit = "SystemDBApi.php?option=campaignCount&campaignStartDate=" + campaignStartDate
            + "&userStartTime=" + campaignStartTime
            + "&campaignEndDate=" + campaignEndDate
            + "&userEndTime=" + campaignEndTime;

        //alert(urlHit);

        var count = $.ajax({
            url: urlHit,
            success: function (result) {
                $("#activeCampaign").html(result);
                //alert(result);
            }
        });
    }

    function totalSmsSent() {
        var smsStartDate = $("#sms-start-date").val();
        var smsStartTime = $("#sms-start-time").val();
        var smsEndDate = $("#sms-end-date").val();
        var smsEndTime = $("#sms-end-time").val();

        var urlHit = "SystemDBApi.php?option=smsCount&smsStartDate=" + smsStartDate
            + "&smsStartTime=" + smsStartTime
            + "&smsEndDate=" + smsEndDate
            + "&smsEndTime=" + smsEndTime;

        //alert(urlHit);

        var count = $.ajax({
            url: urlHit,
            success: function (result) {
                $("#activeSms").html(result);
                //alert(result);
            }
        });

    }

</script>
</body>
</html>
