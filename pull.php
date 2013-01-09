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
            echo ucwords(strtolower($row["city"])) . ", " . $row["state"];
        }
    }
}
?>