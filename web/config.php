<?php
$host="localhost";
$username="root";
$password="";
$db='jeux_drappeaux';

try{
   //connexion avec la base de donnes 
    $con=new PDO("mysql:host=$host;dbname=$db",$username,$password);

    //echo "You are Connected";

}
catch(PDOException $e)
{
	echo  "failedd" .$e->getMessage();
}

    

?>