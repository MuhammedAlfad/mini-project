

<?php
session_start();

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

 $sql = "SELECT * FROM category_tbl";
$result = mysqli_query($con, $sql);

$category = [];  // initialize an array
  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $category[] = $row;  // add each row (associative array) to $category
    }
} else {
    echo "Error fetching categories: " . mysqli_error($con);
}


if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["submit"]))
{
  $name= mysqli_real_escape_string($con,$_POST['veh_name']);
  $model= mysqli_real_escape_string($con,$_POST['veh_mod']);
  $registerno= mysqli_real_escape_string($con,$_POST['veh_reginfo']);
  $contact= mysqli_real_escape_string($con,$_POST['contact_no']);
  $location= mysqli_real_escape_string($con,$_POST['veh_loc']);
  $description= mysqli_real_escape_string($con,$_POST['veh_des']);
  $category= mysqli_real_escape_string($con,$_POST['cat_name']);
  $price= mysqli_real_escape_string($con,$_POST['veh_price']);
 
  //
    $targetDir = "uploads/"; // make sure this folder exists and is writable
    $imageName = basename($_FILES["veh_img"]["name"]);
    $targetFile = $targetDir . uniqid() . "_" . $imageName; // to avoid name collisions
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Optional: Check if image file is an actual image
    $check = getimagesize($_FILES["veh_img"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    // Optional: Limit file types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        die("Only JPG, JPEG, PNG & GIF files are allowed.");
    }

    // Move file to server folder
    if (move_uploaded_file($_FILES["veh_img"]["tmp_name"], $targetFile)) {
    // File moved successfully, proceed with DB insert
    $upload = "INSERT INTO vehicle_tbl (veh_name, veh_model, veh_reg, veh_contact, veh_loc, veh_des, cat_name, veh_img,veh_price,ren_id) 
             VALUES ('$name', '$model', '$registerno', '$contact', '$location', '$description', '$category', '$targetFile','$price', '{$_SESSION['id']}')";
    
  mysqli_query($con,$upload);
} else {
    die("Sorry, there was an error uploading your file.");
}







}




?>


<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    

<style>
 body{
   margin:0;
   background-image: url("img/3.jpg");
   height: 100vh;
   background-size:cover;
   display: flex;
   justify-content: center;
   align-items: center;

}



.options{
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 500px;
    height: 500px;
    flex-direction: column;
      background-color: rgba(0, 0, 0, 0.02);
      backdrop-filter: blur(6px);
         
    border:3px solid rgba(255, 255, 255, 0.1);
    gap: 50px;
    border-radius: 50px;
    box-shadow: 0px 0px 50px 0.5px rgba(0, 0, 0, 0.2);  
}
.options button{
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    position: relative;
    background: transparent;
     color: aliceblue;
  border: none;
    border-radius: 30px;
    box-shadow: 0px 0px 10px 2px rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(6px);
    font-size: 30px;

     display: flex;
    flex-direction: column;
       

    width: 350px;
    height: 50px;
    padding: 40px;
  
    justify-content: center;
    

}
.options button:hover{
  background: rgba(255, 255, 255, 0.2);
  
box-shadow: 0px 0px 10px 2px rgba(0, 0, 0, 0.2);

}

nav{
  color: aliceblue;
  font-family:sans-serif;

  position: relative;
 position:absolute;
 top:0px;
 background-color:rgba(255, 255, 255, 0.1);
 backdrop-filter:blur(30px);
 width: 100%;
 height:5%;
margin:0px;
font-size:20px;
font-weight:bold;
text-transform:uppercase;

}

.upload-vehicle-form{
  display:none;
        position: relative;
   
   align-items:flex-start;
  justify-content:flex-start;

 
    width: 1200px;
    height: 700px;
    flex-direction: row;
      background-color: rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(20px);
         
    border:3px solid rgba(255, 255, 255, 0.1);

    border-radius: 50px;
    box-shadow: 0px 0px 50px 0.5px rgba(0, 0, 0, 0.2);  

 

}

#formgroup1{
  font-size:20px;
  font-weight:bold;
  display:flex;
 color:white;
 gap: 60px 90px ;

  padding:90px;
font-family:sans-serif;
  flex-wrap:wrap;
}

#formgroup1 input{
  color:aliceblue;
  
  width: 300px;
  height:25px;
  border:none;
  background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
       border-radius: 10px;
}

#regnum{

  width: 270px !important;
}

#contact{

  width: 277px !important;
}

#vehloc{

  width: 280px !important;
}

#vehcat{

  width: 278px !important;
}


#formgroup2{
  color:white;
  font-size:20px;
  font-weight:bold;
  position:absolute;
  left: 0px;
  top:250px;
  display:flex;

 gap: 60px 90px ;

  padding:90px;
font-family:sans-serif;
  flex-wrap:wrap;
  display: flex;
  flex-direction: row;
}



textarea{
  position:absolute;
  color:white;
  resize:none;
  top: 40px;
  left:-5px;
  width:440px;
  height:130px;
  border:none;
  background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
       border-radius: 10px;
}
.vehdes{
  position:absolute;
top:80px;
left:620px;
padding:none;


}
select{ border:none;
  background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
       border-radius: 10px;
       color:white;
       
}

#formgroup3{
  position: relative;
  top:25px;
  left:87px;
  color: white;
   font-size:20px;
  font-weight:bold;
  font: sans-serif;
}

#submit{
  position: relative;
  top:200px;
  left:510px;
  color: white;
  
  width: 150px;
  height:40px;
  border: none;
  box-shadow: 0px 0px 5px rgba(255, 255, 255, 0.5);
  background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
       border-radius: 50px;
      font-size: 20px;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-weight: bold;
}
 


.my-vehicles{

    display:none;
        position: fixed;
   
   align-items:flex-start;
  justify-content:flex-start;

 
    width: 90%;
    height: 80%;
    flex-direction: row;
      background-color: rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(20px);
         
    border:3px solid rgba(255, 255, 255, 0.1);

    border-radius: 50px;
    box-shadow: 0px 0px 50px 0.5px rgba(0, 0, 0, 0.2);  

}

.request{

     display:none;
        position: fixed;
   
   align-items:flex-start;
  justify-content:flex-start;
  padding: 50px;
  top:70px;
 
    width: 90%;
    height: 80%;
    flex-direction: row;
      background-color: rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(20px);
         
    border:3px solid rgba(255, 255, 255, 0.1);

    border-radius: 50px;
    box-shadow: 0px 0px 50px 0.5px rgba(0, 0, 0, 0.2); 
    flex-wrap: wrap;
    
}
.req{
  color:white ;
  font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  position: relative;
  display: flex;
  flex-wrap:wrap;
  padding: 40px;
  flex-direction: column;
  border: 2px solid rgba(255, 255, 255, 0.1);
  box-shadow: 2px 2px 4px grey;


}

</style>

</head>
<body >
  
    <div class="options">
       <button id="uv"> Upload Vehicle  </button>
       <button id="req">Requests</button>
       <button id="mv"> My vehicles</button>
    </div>

    <div class="upload-vehicle-form">
      <form action="" method="POST" enctype="multipart/form-data">
  

 <div id="formgroup1">

 <i id="back1" class="fa fa-arrow-left" aria-hidden="true"  style="position:absolute; top: 30px; left:20px; font-size:25px;"></i> 
 
      <div class="element">  Vehicle Name <input type="text" name="veh_name"  id="veh-name">   </div>
 
     <div class="element"> Vehicle Model  <input type="text" name="veh_mod" id=""> </div> 

     <div class="element"> registeration info <input type="text" name="veh_reginfo" id="regnum"> </div> 
            
     <div class="element"> Contact number <input type="text" name="contact_no"id="contact"> </div>
      
    <div class="element">Vehicle Location<input type="text" name="veh_loc" id="vehloc"> </div>

     <div class="element"> Vehicle Price  <input type="text" name="veh_price" id=""> </div> 

</div>

 <div id="formgroup2"> 

      <div class="element"> 
         Vehicle Category <select name="cat_name" value=""  >
        
           <option >Select Type </option>
            <?php foreach($category as $cat): ?>
             <option  value="<?= htmlspecialchars($cat['cat_name']); ?>" >
            <?= htmlspecialchars($cat['cat_name']); ?> </option>
            <?php endforeach ?>
    </select>    </div>

      <div class="vehdes"> Description  <textarea name="veh_des"  rows="5" column="56"></textarea>   </div>

 </div>

 <div id="formgroup3">
      Upload Image  <input type="file" name="veh_img">
     
 </div>
   
 <div id="formgroup4">
     <input type="submit" id="submit" Name="submit">
     
 </div>

      </form>

    </div> 


<!-- my  vehicles -->
    
   <div class="my-vehicles">
      <form action="" method="POST" enctype="multipart/form-data">

      <h1 style="text-align: center; color: white; font-family: sans-serif; font-weight: bold;">
&nbsp;&nbsp; MY VEHICLES
</h1>


 <div id="formgroup1">

 <i class="back3 fa fa-arrow-left" aria-hidden="true"  style="position:absolute; top: 30px; left:20px; font-size:25px;"></i>

     
   <?php 

    $listveh = "SELECT * FROM vehicle_tbl WHERE ren_id = " . $_SESSION['id'];
$veh_result = mysqli_query($con, $listveh);

if (!$veh_result) {
    echo "Query failed: " . mysqli_error($con);
} else {
    while ($veh = mysqli_fetch_assoc($veh_result)) {
        echo "<div style='color: white'>";
        echo "<strong>Name:</strong> " . htmlspecialchars($veh['veh_name']) . "<br>";
        echo "<strong>Model:</strong> " . htmlspecialchars($veh['veh_model']) . "<br>";
        echo "<strong>Price:</strong> ₹" . htmlspecialchars($veh['veh_price']) . "<br>";
        echo "<img src='" . htmlspecialchars($veh['veh_img']) . "' width='150' style='border-radius:10px'><br>";
        echo "<hr>";
         // edit Button
        echo "<a href='booking_vehicle.php?id=" . $veh['veh_id'] . "&status=edit' 
                style='padding:8px 12px; background:green; color:white; text-decoration:none; border-radius:5px; margin-right:10px;'>
                Edit</a>";



        // Remove Button
        echo "<a href='booking_vehicle.php?id=" . $veh['veh_id'] . "&status=remove' 
                style='padding:8px 12px; background:red; color:white; text-decoration:none; border-radius:5px;'>
                Remove</a>";
        echo "</div>";
    }
}

   
   ?>

</div>

</div>


<!-- request view -->

<div class="request">



<i class="back3 fa fa-arrow-left" aria-hidden="true"  style=" color:white; z-index:1000; position:absolute; top: 30px; left:20px; font-size:25px;"></i>


<?php 
$req_list = "
SELECT vehicle_tbl.veh_name, vehicle_tbl.veh_model, vehicle_tbl.veh_img,
       booking_tbl.boo_id, booking_tbl.boo_status, 
       customer_tbl.cus_name
FROM vehicle_tbl
JOIN booking_tbl 
    ON vehicle_tbl.veh_id = booking_tbl.veh_id
JOIN customer_tbl 
    ON booking_tbl.cus_id = customer_tbl.cus_id
WHERE vehicle_tbl.ren_id = '{$_SESSION['id']}'
  AND booking_tbl.boo_status = 'pending'
";

$li = mysqli_query($con, $req_list);

if (!$li) {
    echo "Query failed: " . mysqli_error($con);
} else {
    while ($list = mysqli_fetch_assoc($li)) {
        echo "<div class='req' style='color:white; margin-bottom:20px;'>";

        echo "<strong>Vehicle Name:</strong> " . htmlspecialchars($list['veh_name']) . "<br>";
        echo "<strong>Model:</strong> " . htmlspecialchars($list['veh_model']) . "<br>";
        echo "<strong>Customer Name:</strong> " . htmlspecialchars($list['cus_name']) . "<br>";
        
        echo "<img src='" . htmlspecialchars($list['veh_img']) . "' width='150' style='border-radius:10px'><br><br>";

        // Accept Button
        echo "<a href='booking_vehicle.php?id=" . $list['boo_id'] . "&status=accepted' 
                style='padding:8px 12px; background:green; color:white; text-decoration:none; border-radius:5px; margin-right:10px;'>
                Accept</a>";

        // Reject Button
        echo "<a href='booking_vehicle.php?id=" . $list['boo_id'] . "&status=rejected' 
                style='padding:8px 12px; background:red; color:white; text-decoration:none; border-radius:5px;'>
                Reject</a>";

        echo "</div><hr>";
    }
}
?>



</div>



<nav>   
<i class="fa-regular fa-user"></i> 
    <?php

   echo $_SESSION['username'];
      ?>   
 </nav>



 <script>
const upload  = document.getElementById('uv');
const options = document.querySelector('.options');
const vehicleform = document.querySelector('.upload-vehicle-form');
const submit = document.getElementById('submit');
const myvehicle = document.getElementById('mv');
const myvehiclelist = document.querySelector('.my-vehicles');
const back3 = document.querySelectorAll('.back3');
const back1 = document.getElementById('back1');
const viewrequest = document.querySelector('.request');
const reqbtn = document.getElementById('req');



upload.addEventListener('click',function()
{
  options.style.display='none';
  vehicleform.style.display='flex';
}
);

submit.addEventListener('click',function()
{
  options.style.display='flex';
  vehicleform.style.display='none';
}
);


mv.addEventListener('click',function()
{
   options.style.display='none';
  myvehiclelist.style.display='flex';
  

});


back1.addEventListener('click',function()
{
   options.style.display='flex';
  vehicleform.style.display='none';
  

});


reqbtn.addEventListener('click',function()
{
   viewrequest.style.display='flex';
   options.style.display='none';
  

});


// Loop through the list of buttons you selected
back3.forEach(function(button) {
  // Add the click listener to EACH individual button
  button.addEventListener('click', function() {
    options.style.display = 'flex';
    myvehiclelist.style.display = 'none';
    viewrequest.style.display = 'none';
  });
});

 </script>

</body>
</html>


