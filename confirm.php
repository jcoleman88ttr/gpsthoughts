<?php
require("conn.php");

if(isset($_GET["confirm"])){
    $confirm=  mysqli_real_escape_string($conn, $_GET["confirm"]);
    
    $query=@mysqli_query($conn, "update users set active='yes' where md5(concat(`id`,`email`,`password`)) = '$confirm'");
 
    if(mysqli_affected_rows($conn)){
        echo "confirmed";
        die;
    }else{
        echo "fail";
        die;
    }
}

?>