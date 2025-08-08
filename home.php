<?php
session_start();

 $dbhost="localhost";
 $dbusr="root";
 $dbpas="";
 $db="transport";
 
 $con = mysqli_connect($dbhost,$dbusr, $dbpas, $db);
  if(!$con)
  {
    echo "error connecting <br>";
  }  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

   <style>
    body{
       background: linear-gradient(to bottom,rgba(0, 0, 0, 0.9),rgb(1,1,1));
         background: url('\imgcar.jpg') no-repeat center center fixed;
  background-size: cover;
       height: 1000px;
       margin: 0;

    }


    img{
        width: 90px;
        margin-top:15px ;
        margin-left: 100px;

    }


    #img2{
        margin-left: -7px;
       margin-bottom:12px;
    }

#navigation{
  position: fixed;
    height: 90px;;
    width: 100%;
   z-index: 50000;
    background-color: rgb(23, 28, 34);
}

.text-nav {
    position: absolute;
   font-family: 'Montserrat', sans-serif;


    font-weight: 700;
     text-decoration:none;
     color: aliceblue;
    font-size: 20px;
  
 
    margin-left: 1000px;
    margin-top: -40px ;
    word-spacing: 40px;
   

    
}
.text-nav a{
     text-decoration:none;
     color: aliceblue;
  
}

#img3{
    position: absolute;
    top: 30px;
height: 1310PX;
width:100%;
margin: 0;


}

.ul{
  border-bottom: 2px solid transparent ;
  padding-bottom: 23px;


}

.ul:hover{
  
 border-bottom: 4px solid aqua ;
 
  
}


a:hover{
color: aqua;
}

a.active{
 color: aqua;
}


#Loginbox{
    padding: 30px 60px 10px 60px;
    background: rgb(56, 156, 214);
border: 0px solid  rgb(56, 156, 214);
border-radius: 8px;

height: 7px;
width: 20px;
margin-left: 360px;
margin-top: -34px;


}

#logintext{
  position: relative;
    font-size: 15px;
    text-align: center;
     left: -38px;
    top: -20px;
    

}

#Loginbox:hover{
box-shadow: 0px 0px 20px rgb(56, 156, 214);

}

#logintext:hover{
color:white;

}

button{
    position:absolute;
 font-size: 30px;
 color: aliceblue;
    margin-left: 850px;
    margin-top: -45px;
    background-color:rgb(23, 28, 34) ;
    border: none;
}



h1{
    position: absolute;
       margin: 0;
   z-index: 100;
    font-family: monospace;
    font-size: 100px;
    
    text-shadow:0px 0px 8px aqua ;
}

#h1{
 
    color: aliceblue;
    top: 200px;
    left: 100px;
    
}
#h2{
  
    color: aqua;

    top: 300px;
    left: 100px;
    }
#h3{
   
    color: aliceblue;
    top: 400px;
    left: 100px;
    
}
#h4{
   
    color: rgb(173, 255, 255);
    top: 1050px;
    left: 800px;
    text-shadow: 0PX 0PX 8PX rgb(167, 142, 255);
    
}
h1:hover{

  text-shadow:0px 0px 40px rgb(173, 255, 255) ;

}
p{
    position: absolute;
    font-weight: bold;
    font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    position: absolute;
    color: white;
   font-size: 25px;
    z-index:1000;
    
    top: 1170px;
    left: 700px;
    text-shadow:0px 0px 60px rgb(107, 107, 107) ;
}
p:hover{
text-shadow:0px 0px 60px rgb(173, 255, 255) ;

}


.more{
 position: absolute;
   background-color:rgba(23, 28, 34,0.1);
   backdrop-filter: blur(5px);
  padding: 20px;
  z-index: 100;
  height: 400px;
  width: 200px;
border: 1px solid rgb(57, 58, 58);
border-radius: 0px 0px 0px 0px;
left: 1665px;
top: 98px;
box-shadow:0px 0px 20px  rgba(2, 162, 255, 0.2) ;
display: none;

}

.moption{
font-family: 'Montserrat', sans-serif;


    font-weight: 700;
     text-decoration:none;
     color: aliceblue;
    font-size: 20px;
    margin-left: -10px;
   
  display: flex;
   justify-content:left;
    
}
hr{
  margin-left: -20px;
    width: 130%;
   height: 1px;
   border: none;
 background-color: rgb(85, 87, 87);
}

#homescreen{

  display: block;
}

#browse {
  display: none;
  padding-top: 130px; /* Push content down inside, not the layout */
  text-align: center;
   background: linear-gradient(to bottom,rgb(23, 28, 34),rgb(34, 34, 34));
   height: 100vh;
}

#browse input[type="search"] {
  position: fixed;
  top: 150px; /* controls vertical position */
  left: 50%; /* center horizontally */
  transform: translateX(-50%); /* shift back by half width */

  width: 400px;
  height: 40px;
  padding: 10px;
  font-size: 18px;
  border: 2px solid aqua;
  box-shadow:2px 2px 10px aqua ;
  border-radius: 40px;
  background-color: rgba(0, 0, 0, 0.05);
  color: white;
  backdrop-filter: blur(10px);
  z-index:100;

}

#listvehicle{
  display:flex;
  width: 100%;
  height:100%;
  background-color:rgba(0, 0, 0, 0.4);
  backdrop-filter:blur(30px);
}

.vehicleelement {
border:2px solid white;
width:270px;
height:150px;
display:flex;
padding:40px;
flex-wrap:wrap;
box-shadow:2px 2px 10px aqua;
border-radius:20px;
margin: 80px;


}


.vehicleelement img{
width: 200px;
height: 110px;
position: relative;
left: -20px;
top:-100px;

}

.vehicleelement .book1{
  display: flex;
  position: relative;
  left:-700px;
  top: -20px;
  background-color: rgba(175, 120, 120, 1);
  backdrop-filter: blur(10px);
  border-radius: 10px;
  height: 40px;
  width: 100px;
}

.vehicleelement .book2{
 display: flex;
  position: relative;
  left: -830px;
  top: -20px;
  background-color: rgba(212, 212, 212, 1);
  backdrop-filter: blur(10px);
  border-radius: 10px;
  height: 40px;
  width: 150px;
}

#details{
color:white;
display:flex;
flex-direction:column;


flex-wrap:wrap;



} 

.fullview{
   display: flex;
   width: 100%;
   height: 100%;
   background-color: aqua;

}



   </style>

</head>
<body>

  <div id="navigation">  
  
    <div class="more">  <!more options!>  

      <a  class="moption" href="#">PROFILE</a> <br>
      <hr>
      <a class="moption" href="vehiclereg.html">ADD VEHICLE</a> <br>
      <HR>
      <a class="moption" href="#">BOOKINGSTATUS</a> <br> 
       <hr> 
    </div>

     <nav>
       <img src="img\logo.png">     <img src="img\textlo.png" id="img2">

       <div class="text-nav">
       <a href="#" class="ul" id="home" >Home</a>
       <a href="#" class="ul" id="findvehicle"> Find-Vehicle</a>
        <a href="#" class="ul">About</a>
     <div id="Loginbox">    <a href="login.php" id="logintext" >Login/Signup</a>  </div>
 <button id="option">☰</button>

    </div>
  </nav>
  </div>

 
 <div id="homescreen">
   <h1 id="h1">DRIVE </h1> <BR>
    <h1 id="h2">THE EASY</h1> <br>
    <h1 id="h3"> WAY</h1>
      <h1 id="h4"> WELCOME TO EASYRENT</h1>
    <p>At Easy Rent, we make mobility seamless. Our platform offers a wide range of vehicles to suit every<br> need, 
        backed by a streamlined booking process and reliable support.Whether for business or leisure, <BR> 
          enjoy the freedom to drive without complexity 
        <br>
        <br> — because convenience and quality should always come standard.

</p>
    <img src="img\imgcar.jpg" id="img3">
   
 
    </div>

   <div id="browse">
      <form action="">
         <input type="search" placeholder="Browse Vehicle" >        
      </form>
    
<div id="listvehicle">

<?php
   $listveh = "SELECT * FROM vehicle_tbl ";
$veh_result = mysqli_query($con, $listveh);

if (!$veh_result) {
    echo "Query failed: " . mysqli_error($con);
} else {
    while ($veh = mysqli_fetch_assoc($veh_result)) {
        echo "<div class='vehicleelement'>";
        echo "<div id='details'><strong>Name:</strong> " . htmlspecialchars($veh['veh_name']) . "<br>";
       
        echo "<strong>Price:</strong> ₹" . htmlspecialchars($veh['veh_price']) . "</div><br>";
        echo "<img src='" . htmlspecialchars($veh['veh_img']) . "'>";
        echo "<button class='book1'> BOOK </button>" ;
        echo "<button class='book2'> VIEW </button>" ;
       
       // $sql4= "insert into booking_tbl where    "; 
         echo "</div>";
    }
}
?>



</div>


   </div>

 <div class="fullview">

   
 </div>
 


   
<script>
 const opt = document.getElementById("option");
const more =document.querySelector(".more");
const hs = document.getElementById("homescreen");
const fv = document.getElementById("findvehicle");
const  navlink = document.querySelectorAll(".ul");
const home = document.getElementById("home");
const browse = document.getElementById("browse");
//const view =document.querySelector(".book2");

/* to open and close the more option when mouse moves in and out */
opt.addEventListener('mouseenter',function()
{
  more.style.display='block';
 
});





     more.addEventListener('mouseleave',function()
     {
       more.style.display='none';
     } );


    /* to set the underline border to stay active in the selected link */

    navlink.forEach(link => {
        link.addEventListener('click', function(){
           navlink.forEach(l => {
                l.classList.remove("active");
               
           });
            this.classList.add("active");
        });

      
    });


  
/*  to switch search screen    */

home.addEventListener('click',function(){

hs.style.display='block';
browse.style.display='none';
});

fv.addEventListener('click',function(){

hs.style.display='none';
browse.style.display='flex';

});


/*view.addEventListener('click',function(){

view.style.display='none';
browse.style.display='flex';

});
*/





</script>


</body>
</html>