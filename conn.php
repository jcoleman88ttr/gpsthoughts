<?
session_start();
#database
$dbhost = 'localhost';
$dbuser = 'gpsthoughts';
$dbpass = 'gpsthoughts';
$tryTimes=0;
mysql:
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, "gpsthoughts");
if (mysqli_connect_errno()&&$tryTimes<3) {
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