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
    <h1>dashboard</h1>
    <div class="row ">
        <div class="twelve columns card">
            <div class="card-content">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td>Total registered subscriber</td>
                        <td>Total completed</td>
                        <td>Total minute completed</td>
                    </tr>
                    <tr>
                        <td><h4 id="totalRegistered">0</h4></td>
                        <td><h4 id="totalLesson">0</h4></td>
                        <td><h4 id="totalMinuteCompleted">0</h4></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="height: 200px;"></div>
</div>
<div class="section footer">
    <div class="wrapper">
        <div class="columns copyright">
            <p>RIN- Career Ready Academy | All Rights Reserved | 2016 | Powered by <span>SSD-Tech</span></p>
        </div>
    </div>
</div>
</body>

<script>
    $(document).ready(function () {
        var urlHit = "HomeDBApi.php";

        $.ajax({
            url: urlHit,
            success: function (result) {
                var result = jQuery.parseJSON(result);
                $("#totalRegistered").html(result['totalRegistered']);
                $("#totalLesson").html(result["totalLesson"]);
                $("#totalMinuteCompleted").html(result["totalMinuteCompleted"]);
            }
        });

    })

</script>

</html>
