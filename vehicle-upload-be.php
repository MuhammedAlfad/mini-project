<?php

 $dbhost="localhost";
 $dbusr="root";
 $dbpas="";
 
 $con = mysqli_connect($dbhost,$dbusr, $dbpas);
  if(!$con)
  {
    echo "error connecting <br>";
  }  




 $usedb=" use transport";
 mysqli_query($con,$usedb);

if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["submit"]))
{
  $name= mysqli_real_escape_string($con,$_POST['veh_name']);
  $model= mysqli_real_escape_string($con,$_POST['veh_mod']);
  $registerno= mysqli_real_escape_string($con,$_POST['veh_reginfo']);
  $contact= mysqli_real_escape_string($con,$_POST['contact_no']);
  $location= mysqli_real_escape_string($con,$_POST['veh_loc']);
  $description= mysqli_real_escape_string($con,$_POST['veh_des']);
  $category= mysqli_real_escape_string($con,$_POST['veh_cat']);
 
   

  $sql1="insert into vehicle_tbl (veh_name,veh_model,veh_reginfo,contact_no,veh_loc,veh_des,veh_cat) 
                                values( '$name','$model','$registerno','$contact','$location','$description','$category')";

mysqli_query($con,$sql1);

}




 
?>