<?

if (isset($_SERVER["HTTP_REFERER"]) && preg_match('/http:\/\/www.motojunkyard.com\/(.*)/i', $_SERVER["HTTP_REFERER"])) {
    require("conn.php");

    if (isset($_GET["thought"]) && isset($_GET["category"]) && isset($_GET["latitude"]) && isset($_GET["longitude"])) {

        foreach ($_GET as $key => &$value) {
            $formValue[$key] = mysqli_real_escape_string($conn,trim(urldecode($value)));
        }

        //max 150chars per thought;
        $thought = substr($formValue["thought"], 0, 150);
        //find category ID from array based on Category NAME
        if (in_array($formValue["category"], $thoughtCategories)) {
            $categoryID = array_search($formValue["category"], $thoughtCategories);
        }else{
            //no value found? or invalid ID
            $categoryID = "99"; //other category
        }
        
        
        //todo check strlen
        if (is_numeric($formValue["latitude"]) && is_numeric($formValue["longitude"])) {
            //do add to db query
            mysqli_query($conn,"insert into gpsthoughts (thought, category,latitude,longitude) 
                                values ('$thought','$categoryID','$formValue[latitude]','$formValue[longitude]')");
            echo "posted!"; //do something go to map?
        }
    }
}
?>