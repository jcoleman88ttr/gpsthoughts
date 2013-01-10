<?
if (isset($_SERVER["HTTP_REFERER"]) && preg_match('/http:\/\/www.motojunkyard.com\/(.*)/i', $_SERVER["HTTP_REFERER"])) {
    require("conn.php");

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
    
    if (isset($formValue["thought"]) && isset($formValue["category"]) && isset($formValue["latitude"]) && isset($formValue["longitude"])) {

        //max 150chars per thought;
        $thought = substr($formValue["thought"], 0, 150);
        
        //todo check strlen
        if (is_numeric($formValue["latitude"]) && is_numeric($formValue["longitude"])) {
            //do add to db query
            mysqli_query($conn,"insert into gpsthoughts (thought, category,latitude,longitude) 
                                values ('$thought','$formValue[category]','$formValue[latitude]','$formValue[longitude]')");
            echo "posted!"; //do something go to map?
        }
    }
}
?>