<?php
session_start();
include_once("../config.php");
//lister la liste des jouers
$sql=$con->prepare("select email From Joueurs");
//execution de la requetes 
$executeIsOK=$sql->execute();
//recuperation des resultats
$user=$sql->fetchAll();
//var_dump($user);
//echo $_SESSION['email'];


?>
<Doctype HTML>
<html>
<head>
</head>
<body>
<?php include_once("../navBar.php");?>
<h1> Liste des joueurs </h1>
<u1>
<?php foreach ($user as $liste_joueurs): ?>
	<li>
	<?= $liste_joueurs['email'] ?>
	<a href="supprimer.php?iduser=<?= $liste_joueurs['iduser'] ?>" >Delete</a>
	</li>
	<?php endforeach; ?>
	</u1>
	</body>
	</html>
	