<?
require("conn.php");
if (isset($_SERVER["HTTP_REFERER"]) && preg_match('/http:\/\/www.motojunkyard.com\/(.*)/i', $_SERVER["HTTP_REFERER"])) {

    foreach ($_GET as $key => &$value) {
        $value=trim($value);
        if($value!=null||$value!=""){
            $formValue[$key] = mysqli_real_escape_string($conn,trim(urldecode($value)));
        }
    }

    
    if(isset($formValue["q"])){
        $q=$formValue["q"];
        if(strlen($q)>=3){
            $query=mysqli_query($conn,"select category from gpsthoughts where category like '%$q%' group by category limit 10") or die(mysqli_error($conn));
            while($row=mysqli_fetch_assoc($query)){
                echo $row["category"]."|\n";
            }
        }
    }
    if (isset($_SESSION["login"]) && isset($formValue["visibility"]) && isset($formValue["thought"]) && strlen($formValue["thought"])>3 && isset($formValue["category"]) && strlen($formValue["category"])>3 && isset($formValue["latitude"]) && isset($formValue["longitude"])) {
        //max 150chars per thought;
        $thought = substr($formValue["thought"], 0, 150);
        
        //todo check strlen
        if (is_numeric($formValue["latitude"]) && is_numeric($formValue["longitude"])) {
            //do add to db query
            if($formValue["visibility"]=="1"){
                $visibility="false";
            }else{
                $visibility="true";
            }
            $userid=$_SESSION["userid"];
            mysqli_query($conn,"insert into gpsthoughts (userid, visibility, thought, category,latitude,longitude) 
                                values ('$userid','$visibility','$thought','$formValue[category]','$formValue[latitude]','$formValue[longitude]')");
            echo "posted"; //do something go to map?
        }
    }else{
        echo "error";
    }
}
?>