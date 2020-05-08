<?php
session_start();
include_once("config.php");

$message="";
if(isset($_POST['Register']))
{
$email=$_POST['email'];
$password=$_POST['password'];
$confirmpassword=$_POST['confirmpassword'];
//test des champs
//test d'email
if(empty($_POST['email']))
	
	$message="<li> Votre mail n'est pas valide</li>";
	
	//test longeur de password

	if(empty($_POST['password'])||strlen($_POST['password'])<6)
	$message="<li>password non valide</li>";


if(!empty($_POST['email'])&& !empty($_POST['password'])&& $_POST['password']==$_POST['confirmpassword'])
{

$insert=$con->prepare("INSERT INTO  joueurs(email,password) VALUES('$email','$password')");
$insert->bindPARAM(':email',$email);
$insert->bindPARAM(':password',$password);
$insert->execute();
if($insert){
echo"user has been registered successfully";
header("location:joue.php");

}else echo "email or password not correct";
}
}
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title> Register</title>
</head>
<body>
<h1>Register</h1>
<span> or <a href="connect.php">Login here </a></span>	
<form  action="" method="POST">
      

        <p> Email</p>
           <input type="email"  placeholder= "exemple@exemple "name="email" > 
         
              <p>Password</p>
                <input type="text" placeholder= " Password"name="password" >
               <p> Repeat Password</p>
                <input type="password" placeholder= " RepeatPassword..."name="confirmpassword" ><br></br>

               <input type="submit" name="Register" value="Register" class="form_bouton">
                  
              
</form> 
<?php if (!empty($message)){ ?>
<div id="message"> <?php echo $message ?></div> <?php } ?>
</body>
</html>
