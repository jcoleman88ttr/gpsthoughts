<?php
require("conn.php");
if (isset($_SERVER["HTTP_REFERER"]) && preg_match('/http:\/\/www.motojunkyard.com\/(.*)/i', $_SERVER["HTTP_REFERER"])) {

    if (isset($_GET["username"]) && isset($_GET["password"])) {
        $username = mysqli_real_escape_string($conn, $_GET["username"]);
        $password = md5($_GET["password"]);

        $query = mysqli_query($conn, "select username, password,userid from users where username='$username' and password='$password' limit 1");
        $row = mysqli_fetch_assoc($query);

        if ($row["username"]) {
            $_SESSION["login"] = "true";
            $_SESSION["userid"] = $row["userid"];
            $loginID=md5($row["userid"].$row["username"]."gpsthoughts");
                setcookie("loggedin", $loginID, time()+2592000);
            echo "loggedin";
            die;
        } else {
            echo "error";
            die;
        }
    }
}
?>
