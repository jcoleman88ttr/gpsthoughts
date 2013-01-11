<?php
require("conn.php");
if (isset($_SERVER["HTTP_REFERER"]) && preg_match('/http:\/\/www.motojunkyard.com\/(.*)/i', $_SERVER["HTTP_REFERER"])) {
    if (isset($_GET["username"]) && isset($_GET["password"]) && isset($_GET["email"])) {

        $username = mysqli_real_escape_string($conn, $_GET["username"]);
        $password = $_GET["password"];
        $email = mysqli_real_escape_string($conn, $_GET["email"]);

        if (strlen($username) < 4) {
            echo "short";
            die;
        }
        if (strlen($username) > 10) {
            echo "long";
            die;
        }

        if ($password == "" || strlen($password) < 4) {
            echo "shortpassword";
            die;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "bademail";
            die;
        }

        $query = mysqli_query($conn, "select username,email from users where username='$username' or email='$email' limit 1");
        $row = mysqli_fetch_assoc($query);

        if ($row["username"]) {
            echo "userexists";
            die;
        }

        if ($row["email"]) {
            echo "emailexists";
            die;
        }
        $password = md5($password);
        mysqli_query($conn, "insert into users (username, password, email) values ('$username','$password','$email')");

        $deleteid = md5(mysqli_insert_id($conn) . $email . $password);
        if (mail($email, "Activate your account", "activation code... http://www.motojunkyard.com/scriptor/gpsthoughts/confirm.php?confirm=" . $deleteid)) {
            echo "emailsent";
            die;
        }
    }
}
?>
