<?

if (isset($_SERVER["HTTP_REFERER"]) && preg_match('/http:\/\/www.motojunkyard.com\/(.*)/i', $_SERVER["HTTP_REFERER"])) {
    require("conn.php");
    //pull city by location to tell user where they are located
    if (isset($_GET["latitude"]) && isset($_GET["longitude"])) {

        foreach ($_GET as $key => &$value) {
            $formValue[$key] = mysqli_real_escape_string($conn,trim(urldecode($value)));
        }
        $q = "select city, state from zipcodes order by ABS(ABS(LATITUDE-$formValue[latitude]) + ABS(LONGITUDE-$formValue[longitude])) ASC LIMIT 1";

        $query = mysqli_query($conn,$q);
        while ($row = mysqli_fetch_assoc($query)) {
            
            //check for near thoughts
            $stringQuery="select round(SQRT(POW(69.1 * (a.latitude - $formValue[latitude]), 2) + POW(69.1 * (a.longitude-$formValue[longitude]) * COS(a.latitude / 57.3), 2))) AS distance from gpsthoughts a having distance < 10 limit 100";
            $queryu=mysqli_query($conn,$stringQuery);
            $result=  mysqli_fetch_assoc($queryu);
           
            echo ucwords(strtolower($row["city"])) . ", " . $row["state"]."|".@mysqli_num_rows($queryu);
        }
    }
}
?>