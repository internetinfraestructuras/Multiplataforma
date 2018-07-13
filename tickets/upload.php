<?php

require_once('../config/db_config.php');

// Create connection
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASENAME);
 
 if($_SERVER['REQUEST_METHOD'] == 'POST')
 {
	 $DefaultId = 0;
 
 $ImageData = $_POST['image_path'];
 
 $ImageName = $_POST['image_name'];

 $GetOldIdSQL ="SELECT id FROM ImageToServerTable ORDER BY id ASC";
 
 $Query = mysqli_query($conn,$GetOldIdSQL);
 
 while($row = mysqli_fetch_array($Query)){
 
 $DefaultId = $row['id'];
 }
 
 $ImagePath = "uploads/$DefaultId.png";
 
 $ServerURL = "http://ftth.internetinfraestructuras.es/tikets/$ImagePath";
 
 $InsertSQL = "insert into ImageToServerTable (image_path,image_name) values ('$ServerURL','$ImageName')";
 
 if(mysqli_query($conn, $InsertSQL)){

 file_put_contents($ImagePath,base64_decode($ImageData));

 echo "Your Image Has Been Uploaded.";
 }
 
 mysqli_close($conn);
 }else{
 echo "Not Uploaded";
 }

?>