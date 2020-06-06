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
	
	$message="<li>mail non valide</li>";
	
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> 
<link href="style.css" rel="stylesheet" type="text/css" >
</head>
<body>

 <div>
<form  action="" method="POST">
	<div class="container">
    <div class="row">
      <div class="col-md-4 form-div">
      	<h2 align="center">Login</h2>
      	<hr class="mb-3">
      	<div>
        <label> Email</label>
           <input class="form-control" type="email"  placeholder= "exemple@exemple. "name="email" required /> 
         </div>
         <div>
              <label>Password</label>
                <input class="form-control" type="text" placeholder= " Password"name="password" required />
            </div>
                <hr class="mb-3">
                 <input  class="btn btn-primary btn-block" type="submit" name="Login" value="Login" class="form_bouton"/>
             </div>
         </div>
     </div>
</form> 
       </div>
<?php if (!empty($message)){ ?>
<div id=message> <?php echo $message ?></div> <?php } ?>

</body>
</html>
