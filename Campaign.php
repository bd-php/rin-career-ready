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
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
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
    <h1>Campaign</h1>
    <div class="row ">

        <div class="twelve columns card">
            <div class="card-content">
                <div class="two columns">
                    <select id="callCount">
                        <option value="0">Total Miss Calls</option>
                        <option value="1">Unique Miss Calls</option>
                        <option value="2">Total Miss Call After Registration</option>
                    </select>
                </div>
                <div class="two columns">
                    <input type="submit" value="Search" onclick="callCountTotal()">
                </div>
                <div class="two columns">
                    <h1 id="callCountTotal">Total</h1>
                </div>
            </div>
        </div>

        <div class="twelve columns card">
            <div class="card-content">
                <div class="two columns">
                    <select id="regCount">
                        <option value="0">All Registration Count</option>
                        <option value="1">Count of Drop out during registration</option>
                        <option value="2">Dropout percentage at each stage of registration</option>
                    </select>
                </div>
                <div class="two columns">
                    <input type="submit" value="Search" onclick="regCountTotal()">
                </div>
                <div class="two columns">
                    <h1>Total</h1>
                    <p id="regCountTotal"></p>
                </div>
            </div>
        </div>

        <div class="twelve columns card">
            <div class="card-content">
                <div class="two columns">
                    <select id="moduleCount">
                        <option value="0">Count of each module completion</option>
                        <option value="1">Count of each module started</option>
                        <option value="2">Total completion number</option>
                        <option value="3">Count of Repeat callers after completion</option>
                        <option value="4">Drop Out Stage(module number)</option>
                        <option value="5">Drop Out Rate(total drop out/total registered)</option>
                        <option value="6">Days taken to complete course</option>
                    </select>
                </div>
                <div class="two columns">
                    <input type="submit" value="Search" onclick="moduleCountTotal()">
                </div>
                <div class="two columns">
                    <h1>Total</h1>
                    <p id="moduleCountTotal"></p>
                </div>
            </div>
        </div>

        <div class="twelve columns card">
            <div class="card-content">
                <div class="two columns">
                    <select id="regionCount">
                        <option value="0">All Region</option>
                        <?php
                        include_once("Config.php");
                        $conn = new mysqli($Server, $UserID, $Password, $Database);

                        $sql = "SELECT 10000 AS id, 'others' AS PostalCode UNION ALL SELECT * FROM tbl_postal_code";

                        $result = $conn->query($sql);

                        $value = 1;

                        while ($row = $result->fetch_assoc()) {
                            $postal_code = $row["PostalCode"];
                            echo "<option " . "value=" . $value . ">" . $postal_code . "</option>";
                            $value++;
                        }

                        mysqli_close($conn);
                        ?>
                    </select>
                </div>
                <div class="two columns">
                    <input type="submit" value="Search" onclick="regionCountTotal()">
                </div>
                <div class="two columns">
                    <h1 id="regionCountTotal">Total</h1>
                </div>
            </div>
        </div>

    </div>
</div>


<div style="height: 150px"></div>

</div>
<div class="section footer">
    <div class="wrapper">
        <div class="columns copyright">
            <p>RIN- Career Ready Academy | All Rights Reserved | 2016 | Powered by <span>SSD-Tech</span></p>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        //$("#callCountTotal, #regCountTotal, #moduleCountTotal, #regionCountTotal").hide();
    });

    function callCountTotal() {
        var menu = $("#callCount").val();

        var urlHit = "CampaignDBApi.php?option=" + 'callCount' + "&menu=" + menu;

        var count = $.ajax({
            url: urlHit,
            success: function (result) {
                $("#callCountTotal").html("<h1>Total = " + result + "</h1>");
            }
        });
    }

    function regCountTotal() {
        var menu = $("#regCount").val();

        var urlHit = "CampaignDBApi.php?option=" + 'regCount' + "&menu=" + menu;

        var count = $.ajax({
            url: urlHit,
            success: function (result) {
                $("#regCountTotal").html(result);
            }
        });
    }

    function moduleCountTotal() {
        var menu = $("#moduleCount").val();

        var urlHit = "CampaignDBApi.php?option=" + 'moduleCount' + "&menu=" + menu;

        var count = $.ajax({
            url: urlHit,
            success: function (result) {
                $("#moduleCountTotal").html(result);
            }
        });
    }

    function regionCountTotal() {
        var menu = $("#regionCount option:selected").text();
        if ($("#regionCount option:selected").val() == 0) {
            menu = "all";
        }

        var urlHit = "CampaignDBApi.php?option=" + 'regionCount' + "&menu=" + menu;

        var count = $.ajax({
            url: urlHit,
            success: function (result) {
                $("#regionCountTotal").html("<h1>Total = " + result + "</h1>");
            }
        });
    }

</script>

</body>
</html>
