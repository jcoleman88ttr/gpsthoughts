<?
if (isset($_SERVER["HTTP_REFERER"]) && preg_match('/http:\/\/www.motojunkyard.com\/(.*)/i', $_SERVER["HTTP_REFERER"])) {
    require("conn.php");
    //pull city by location to tell user where they are located
    if (isset($_GET["latitude"]) && isset($_GET["longitude"]) && isset($_GET["type"])) {

        foreach ($_GET as $key => &$value) {
            $value = trim($value);
            if ($value != null || $value != "") {
                $formValue[$key] = mysqli_real_escape_string($conn, trim(urldecode($value)));
            }
        }

        //auto complete sql
        if ($formValue["type"] == "getCity") {
            $q = "select city, state from zipcodes order by ABS(ABS(LATITUDE-$formValue[latitude]) + ABS(LONGITUDE-$formValue[longitude])) ASC LIMIT 1";
            $query = mysqli_query($conn, $q);
            while ($row = mysqli_fetch_assoc($query)) {

                //check for near thoughts
                $stringQuery = "select round(SQRT(POW(69.1 * (a.latitude - $formValue[latitude]), 2) + POW(69.1 * (a.longitude-$formValue[longitude]) * COS(a.latitude / 57.3), 2))) AS distance from gpsthoughts a having distance < 10 limit 100";
                $queryu = mysqli_query($conn, $stringQuery);
                $result = mysqli_fetch_assoc($queryu);

                echo ucwords(strtolower($row["city"])) . ", " . $row["state"] . "|" . @mysqli_num_rows($queryu);
            }
        }

        //populate google maps sql
        if ($formValue["type"] == "populateMap") {
            $queryText="select a.latitude, a.longitude, a.thought, a.category, round(SQRT(POW(69.1 * (a.latitude - $formValue[latitude]), 2) + POW(69.1 * (a.longitude-$formValue[longitude]) * COS(a.latitude / 57.3), 2))) AS distance from gpsthoughts a group by a.latitude, a.longitude having distance < 10 limit 100";
            $query = mysqli_query($conn, $queryText) or die(mysqli_error($conn));

            function parseToXML($htmlStr) {
                $xmlStr = str_replace('<', '&lt;', $htmlStr);
                $xmlStr = str_replace('>', '&gt;', $xmlStr);
                $xmlStr = str_replace('"', '&quot;', $xmlStr);
                $xmlStr = str_replace("'", '&#39;', $xmlStr);
                $xmlStr = str_replace("&", '&amp;', $xmlStr);
                return $xmlStr;
            }

            header("Content-type: text/xml");
            echo '<markers>';

            while ($row = mysqli_fetch_assoc($query)) {
                echo '<marker ';
                echo 'name="' . parseToXML($row['category']) . '" ';
                echo 'address="' . parseToXML($row['thought']) . '" ';
                echo 'lat="' . $row['latitude'] . '" ';
                echo 'lng="' . $row['longitude'] . '" ';
                echo 'type="" ';
                echo '/>';
            }
            echo '</markers>';
        }
    }
}
?>