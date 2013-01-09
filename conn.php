<?

#database
$dbhost = 'localhost';
$dbuser = 'motojunkyard_sql';
$dbpass = '';
$tryTimes=0;
mysql:
$conn = new mysqli($dbhost, $dbuser, $dbpass, "gpsthoughts");;
if ($conn->connect_errno&&$tryTimes<3) {
    $tryTimes++;
    goto mysql;
}


$thoughtCategories = array(
    "1" => "Technology",
    "2" => "Outdoors",
    "3" => "Sports",
    "4" => "Food" 
 );
?>