<?php
require 'conn.php';


$query=  mysqli_query($conn, "select latitude, longitude, city, state from zipcodes where zipcode between 15001 and 19099");
$i=0;
while($row=  mysqli_fetch_assoc($query)){
    $i++;
    mysqli_query($conn,"insert into gpsthoughts (thought, category,latitude,longitude) 
                                values ('Generated Thought $i','AutoMated!','$row[latitude]','$row[longitude]')");
    echo 1;
}
?>
