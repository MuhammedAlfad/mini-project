<!DOCTYPE html>
<html lang="en">
<head>
    
    <title>Login</title>

    <style>
      body{
      

        
      }
    
    #login{

      
       border: 1px solid rgb(245, 237, 237);
      width: 358px;
      height: 368px;
     
     margin-top: 160px;  
      
      border-radius: 3px;
      border-color:grey;
      box-shadow: 0px 0px 8px rgb(85, 84, 84);
      background-color: rgb(0, 0, 0);
    
     
      
      
     }

     input{
         color: rgb(255, 255, 255);
         height: 30px;
         width: 240px;
         margin-bottom: 5px;
         background-color: rgb(44, 44, 44);
         border: 0.0000001em solid rgb(139, 139, 139);


       }

       #loginbt{
        margin-top: 40PX;
         background-color:  rgb(94, 109, 179) ;

       }

       #loginfont{
        color: aliceblue;
        font-family:sans-serif  ;
        font-size: 30px;
        letter-spacing: 12px;
        

       }

       #sublogin{
         color: aliceblue;
   
       border: 1px solid rgb(245, 237, 237);
      width: 358px;
      height: 70px;
      
     
      border-radius: 7px;
      border-color:grey;
      box-shadow: 0px 0px 8px rgb(85, 84, 84);
      background-color: rgb(0, 0, 0);
     margin-top: 40px;
       }

      #signbt
      {
        font-family: serif;
        color: rgb(183, 196, 255) ;
        font-size: 16px;
        margin-left: -15px;
        width: 90px;
        background:none ;
        border:none
              
      }

#enterdetails{
      margin-top: 50px;
 color: aliceblue;
        font-family:sans-serif  ;
        font-size: 30px;
        letter-spacing: 12px;
        margin-bottom: 50px;

}

#signsubmit{
  font-family: Arial, Helvetica, sans-serif;
  font-weight: bold;

  font-size: 15px;
   margin-top: 55px;
   height: 40px;
   background-color:  rgb(94, 109, 179) ;
   border-radius: 8px;
   
   
}

#signfont{
   margin-bottom: 1px;
  

}

#back-sign{
  color:   rgb(245, 237, 237);
   background:none;
   border: 1px solid  rgb(160, 160, 160) ;
   border-radius:10%;
   box-shadow:0px 0px 8px  rgb(85, 84, 84);
;
   width:37px;
   height:37px;
   font-weight:bold;
   font-size:30px;  

   margin-left:-470px;
 margin-top:0px;

}

      #signin{


       color: white;
       

        border: 1px solid rgb(245, 237, 237);
      width: 540px;
      height: 770px;
     
     margin-top: 80px;  
   
      border-radius: 3px;
      border-color:grey;
      box-shadow: 0px 0px 8px rgb(85, 84, 84);
      background-color: rgb(0, 0, 0);


        display: none;
      }

select{
 position:relative;
 display:flex;
 border-radius:20px;
}
    </style>
</head>
<body background="img\5.jpg">

  
    <center>
    <form action="signinbackend.php" method="post" >


      <div id="login" >
        <br> 
        <br> 
     
        <h1 id="loginfont">LOGIN </h1>
        <br>       
        <input type="text" name="loemail" placeholder="E-Mail" id="input" > <br> 
        <input type="password" name="lopassword" placeholder="Password" id="input"> <br> <br>
        <input type="submit" name="login" value="Login" id="loginbt">
      </div> 
      
        
        <div id="sublogin">
<br>
             Don`t Have An Account? <input type="button"  value="Sign-up" class="show" ID="signbt">
         </div>

    </form>
        
    
     

 <form action="signinbackend.php" method="POST" >
     <div id="signin">
  <br>
    <input type="button" name="back-sign" value="<" id="back-sign">  
        <h1 id="enterdetails">ENTER DETAILS</h1> <br> <br>
        Enter Username <br> <input type="text" name="username" placeholder="USER-NAME" id="signfont"> <br>  <br>
       Enter E-Mail <br> <input type="text" name="email" placeholder="E-Mail" id="signfont"> <br>  <br>
       Enter Password <br> <input type="password" name="password" placeholder="Password" id="signfont"> <br> <br>
       Enter Contact Number <br> <input type="text" name="contact" placeholder="NUMBER" id="signfont"> <br> <br>  <br>

      
       Gender: 
      <select name="gender">
          
           <option value="male">MALE </option>
            <option value="female">Female </option>
    </select> <br>
       Signup As:
    
        <select name="role" id="role">
          <option value="customer">Customer</option>
          <option value="renter">renter</option>
        </select>
 

      
       <input type="submit" value="submit" name="submit" id="signsubmit">
  </form>
      
   </div> 
   
   <script>
    const backsi=document.getElementById('back-sign');
 
  const signsub=document.getElementById('signsubmit');
  const show2 = document.querySelector('.show');
  const signup = document.getElementById('signin');
  const log = document.getElementById('login');
  const log2 =document.getElementById('sublogin');

   show2.addEventListener('click',function()
     {
        log.style.display='none';
        log2.style.display='none';
        signup.style.display='block';
     });


   signsub.addEventListener('click',function()
     {
        signup.style.display='none';
        log.style.display='block';
        log2.style.display='block';
        
     });

      backsi.addEventListener('click',function()
  {
       signup.style.display='none';
        log.style.display='block';
        log2.style.display='block';
  });  

   </script>
   

   
</center>

</body>
</html>