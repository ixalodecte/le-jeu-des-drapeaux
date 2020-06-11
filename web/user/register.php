
<?php
session_start();
include_once("../config.php");

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

$insert=$con->prepare("INSERT INTO  joueurs(email,password) VALUES(?, ?)");
$insert->bindPARAM(':email',$email);
$insert->bindPARAM(':password',$password);
$insert->execute([$email, $password]);
if($insert){
echo "user has been registered successfully";
header("location:/jouer/choixQuestionnaire.php");
}
else 
echo "email or password not correct";
}
}
//requete pour supprimer les donnes 


?>