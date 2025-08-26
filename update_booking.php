<?php
$con = mysqli_connect("localhost","root","","transport");
$boo_id = $_GET['boo_id'];
mysqli_query($con, "UPDATE booking_tbl SET boo_status='available' WHERE boo_id='$boo_id'");
?>
