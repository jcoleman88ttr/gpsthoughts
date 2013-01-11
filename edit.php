<?php

require("conn.php");


foreach ($_GET as $key => &$value) {
    $value = trim($value);
    if ($value != null || $value != "") {
        $formValue[$key] = mysqli_real_escape_string($conn, trim(urldecode($value)));
    }
}

if(isset($formValue["deleteid"])){
    
}
?>
