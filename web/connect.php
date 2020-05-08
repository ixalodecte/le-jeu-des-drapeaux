<?php
session_start();
include_once("config.php");
$message="";
// test des champs
if(isset($_POST['Login']))
{
$email=$_POST['email'];
$password=$_POST['password'];
	// en cas des champs non valides:
	if(empty($_POST['email']))
	
	$message="<li>Votre mail n'est pas valide</li>";
	
	//test de  password

	else if(empty($_POST['password']))
	{
		$message="<li>password non valide</li>";
	}
if(!empty($_POST['email'])&& !empty($_POST['password']))
{

	//recherche si le joueur existe
$sql="SELECT * FROM joueurs WHERE email='$email' and password='$password'";
$selec=$con->prepare($sql);

$selec->execute();
if($selec->rowCount()>0)
{
	//echo"you are connected ";
	$data=$selec->fetchAll();
	$_SESSION['email']=$data['email'];
	$_SESSION['password']=$data['password'];
	//echo "welcome";
	header("location:joue.php");
	
}else 
echo " invalid email or password";




}
}

?>



</!DOCTYPE html>
<html>
<head>
<title> LOGIN</title>
</head>
<body>
<h1>Login</h1>
 
<form  action="" method="POST">
        <p> Email</p>
           <input type="email"  placeholder= "exemple@exemple. "name="email" > 
         
              <p>Password</p>
                <input type="text" placeholder= " Password"name="password" >
                 <input type="submit" name="Login" value="Login" class="form_bouton">
           </form> 
<?php if (!empty($message)){ ?>
<div id="message"> <?php echo $message ?></div> <?php } ?>

</body>
</html>
