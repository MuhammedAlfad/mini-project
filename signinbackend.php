<?php
 $dbhost="localhost";
 $dbusr="root";
 $dbpas="";
 

session_start();

  $con = mysqli_connect($dbhost,$dbusr, $dbpas);
  if(!$con)
  {
    echo "error connecting <br>";
  }  


 $usedb=" use transport";
 mysqli_query($con,$usedb);
  
 //sigin details to server 

if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["submit"]))
{  
   

   $email = mysqli_real_escape_string($con,$_POST["email"]);
 $username = mysqli_real_escape_string($con,$_POST["username"]);
 $pass= password_hash($_POST["password"],PASSWORD_DEFAULT);
 $contact = mysqli_real_escape_string($con,$_POST["contact"]);
 $gender = mysqli_real_escape_string($con,$_POST["gender"]);
 $role = mysqli_real_escape_string($con,$_POST["role"]);




 if($role == "customer"){
 $inscus="insert into customer_tbl (cus_mail, cus_name, cus_pass, cus_no, cus_gen) values('$email','$username','$pass','$contact','$gender')";
 $insertcustomer= mysqli_query($con,$inscus);
 header("Location: login.php"); 
 exit;
  
} else if($role == "renter"){
      $inscus="insert into renter_tbl (ren_mail, ren_name, ren_pass, ren_no, ren_gen) values('$email','$username','$pass','$contact','$gender')";
 $insertcustomer= mysqli_query($con,$inscus);
 header("Location: login.php"); 
 exit;
  }
}



//login

if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["login"])){

$loemail =mysqli_real_escape_string($con,$_POST["loemail"]);
$lopassword=$_POST["lopassword"];

$get1 = "select * from customer_tbl where cus_mail ='$loemail'";
$wa1 =mysqli_query($con,$get1);

$get2 = "select * from renter_tbl where ren_mail ='$loemail'";
$wa2 =mysqli_query($con,$get2);


if(mysqli_num_rows($wa1)==1)
{
  $result =mysqli_fetch_assoc($wa1);
  $hash_pass=$result['cus_pass'];

  if(password_verify($lopassword,$hash_pass))
  {
     $_SESSION['username'] = $result['cus_name'];
     $_SESSION['id'] =$result['cus_id'];
     $cus_id=$result['cus_id'];
     
   $login1="insert into customer_session_tbl (cus_id) values('$cus_id')";
   mysqli_query($con,$login1);

     header("location: home.php");
      exit();

  } else{
    echo "wrong password";
    echo "Entered: " . $lopassword . "<br>";
echo "Stored: " . $hash_pass . "<br>";

  }


}else { echo "invalid email"; }





if(mysqli_num_rows($wa2)==1)
{
  $result =mysqli_fetch_assoc($wa2);
  $hash_pass=$result['ren_pass'];

  if(password_verify($lopassword,$hash_pass))
  {
     $_SESSION['username'] = $result['ren_name'];
     $_SESSION['id'] =$result['ren_id'];
     $ren_id = $result['ren_id'];

        $login2="insert into renter_session_tbl (ren_id) values('$ren_id')";
   mysqli_query($con,$login2);

     header("location: rhome.php");
      exit();
  } else{
    echo "wrong password";
    echo "Entered: " . $lopassword . "<br>";
echo "Stored: " . $hash_pass . "<br>";

  }


}else { echo "invalid email"; }

}


?>