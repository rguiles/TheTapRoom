<?php
include "config.php";
if (isset($_POST['but_submit'])) {
    $uname = mysqli_real_escape_string($con, $_POST['txt_uname']);
    if ($uname != "") {
        $sql_query = "Select count(*) as cntUser from Customer where customer_id='" . $uname . "'";
        $result = mysqli_query($con, $sql_query);
        $row = mysqli_fetch_array($result);
        $count = $row['cntUser'];
        if ($count > 0) {
            $_SESSION['uname'] = $uname;
            header('Location: index.php?id='. $uname );
        }
    }
}
?>

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="css/swipe.css" rel="stylesheet">
</head>
<html>

<body>
    <div class="wrapper fadeInDown">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-12">
                    <div class="popup-btn" onclick="document.getElementById('id01').style.display='block'">
                        <a href="admin.php">Admin</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="formContent">
            <div class="fadeIn first">
                <br>
                <img src="/images/TTR.png" id="icon" alt="User Icon" />
                <hr>
            </div>
            <form method="post" action="">
                <div id="div_login">
                    <input type="text" id="txt_uname" class="fadeIn second" name="txt_uname" placeholder="Swipe card to begin">
                    <input type="submit" class="fadeIn third" name="but_submit" id="but_submit" value="Log In">
                </div>
            </form>
        </div>
    </div>

</body>

</html>