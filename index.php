<?php
session_start();
include_once 'Config.php';

if (isset($_SESSION['user']) != "") {
    header("Location: home.php");
}

if (isset($_POST['btn-login'])) {

    $username = $_POST['username'];
    $pass = $_POST['pass'];

    $sql = "select * from tbl_login where username='$username'";
    //echo $sql;
    $stmt = $pdo->query($sql);
    $row = $stmt->fetchObject();

    //echo $row->password;
    //echo md5($pass);
    //exit(0);

    if ($row->password == md5($pass)) {
        $_SESSION['user'] = $row->username;
        header("Location: home.php");
    } else {
        ?>
        <script>alert('wrong details');</script>
        <?php
    }
}
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

    <script type="text/javascript" src="js/jquery.mmenu.all.min.js"></script>
    <link type="text/css" rel="stylesheet" href="css/jquery.mmenu.all.css"/>
</head>

<body class="login-bg">

<div class="login">

    <div class="login-page">

        <div class="form">
            <div class="rin-logo">
                <img src="images/rin.png" width="161" height="132">
            </div>

            <form class="register-form">
                <input type="text" placeholder="name"/>
                <input type="password" placeholder="password"/>
                <input type="text" placeholder="email address"/>
                <button>create</button>
                <p class="message">Already registered? <a href="#">Sign In</a></p>
            </form>
            <form class="login-form" method="post">
                <input name="username" type="text" placeholder="username"/>
                <input name="pass" type="password" placeholder="password"/>
                <button name="btn-login">login</button>
                <p class="message">Not registered? <a href="#">Create an account</a></p>
            </form>
        </div>
    </div>

</div>
<script>
    $('.message a').click(function () {
        $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
    });
</script>

</body>
</html>
