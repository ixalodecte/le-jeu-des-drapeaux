
<?php
session_start();
include_once("config.php");

$message="";
if(isset($_POST['Register']))
{
$email=$_POST['email'];
//hash password
$pwd=$_POST['password'];
$password=MD5($pwd);
$confirmpassword=$_POST['confirmpassword'];

//test des champs
//test d'email
if(empty($_POST['email'])){
	
	$message="mail is required";
}
	
	//test longeur de password

	if(empty($_POST['password']))
	$message="password non valide";


if(!empty($_POST['email'])&& !empty($_POST['password'])&& $_POST['password']==$_POST['confirmpassword'])
{

$insert=$con->prepare("INSERT INTO  joueurs(email,password) VALUES('$email','$password')");
$insert->bindPARAM(':email',$email);
$insert->bindPARAM(':password',$password);
$insert->execute();
if($insert){
echo "user has been registered successfully";
header("location:joue.php");
}
else 
echo "email or password not correct";
}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
 <title> Register</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> 

<link href="style.css" rel="stylesheet" type="text/css" >

</head>

<body>
<form  action="" method="POST" onsubmit="return myfun()" name="vform" >
  <div class="container">
    <div class="row">
      <div class="col-md-4 form-div">
        <h2 align =center>Register
 </h2>
<hr class="mb-3">
<div>
  <div id="email_div">
<label> Email</label>
           <input  class="form-control" type="email"  placeholder= "exemple@exemple "name="email"> 
           <div id="email_error">
           </div>
         </div>
           <div>
           <div id="password_div"> 
        <label>Password</label>
                <input class="form-control" type="password" placeholder= " Password"name="password" >
                <div id="password_error">
                </div>
            </div>
          </div>
              <div >
                <div id="confirmpassword_div">
               <label> Repeat Password</label>
                <input  class="form-control" type="password" placeholder= " RepeatPassword..."name="confirmpassword" >
                <div id="confirmpassword_error"/>
              </div>
            </div>
             <hr class="mb-3">
             <div>
             <input  class="btn btn-primary btn-block" type="submit" name="Register" value="Register" class="form_bouton"  />
           </div>
             <p class="text-center">
             Member ? <a href="connect.php">Login here </a>
             
             </p>
              </div>

         </div>

      </div>
</form> 
</body>
</html>
<script type="text/javascript">
  //getting all input
  var email=document.forms["vform"]["email"];
  var password=document.forms["vform"]["password"];
  var confirmpassword=document.forms["vform"]["confirmpassword"];

  var email_error=document.getElementById("email_error");
  var password_error_=document.getElementById("password_error");
    var confirmpassword_error=document.getElementById("confirmpassword_error");
      //setting all event
      email.addEventListener("blur",emailVerify,true);
      password.addEventListener("blur",passwordverify,true);
      confirmpassword.addEventListener("blur",confirmpasswordverify,true);
      function myfun(){
        //verification email
        if(email.value== "")
      {
         document.getElementById('email_div').style.color = "red";
       email_error.textContent="email is required";
       return false;
      }
      //validation de password
      if (password.value == "") {

        document.getElementById('password_div').style.color = "red";
        password_error.textContent = "Password is required";
        return false;
      }
      //verification strlen de password >6
      if(password.value.length <6){
        document.getElementById('password_div').style.color = "red";
        password_error.innerHTML = "Password is +6 characters";
      }
      //verification de deux password  match

   if (password.value != confirmpassword.value) {
    document.getElementById('confirmpassword_div').style.color = "red";
    confirmpassword_error.innerHTML = "The two passwords do not match";
    return false;
  }
}
    function emailverify(){
      if(email.value!=""){
        return true;
      }
    }
    function passwordverify()
    {
      if(password.value!=""){
        return true;
      }
    }

 </script>