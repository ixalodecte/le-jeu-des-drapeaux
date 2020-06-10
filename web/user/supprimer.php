<?php
// preparation de la requete
include_once("config.php");
// esemple suppression de la premiere ligne si le tableau est vide on risque rien 
$user=$con->prepare ('DELETE FROM joueurs where iduser=:num LIMIT 1');
$user->bindValue(':num',$_GET['iduser'],PDO::PARAM_INT);
//execution de la requete
$executeIsOK=$user->execute();
if($executeIsOK){
	$message="le joueur a ete supprime !";
}
else{
	$message="Echecde suppression de joueur ";
}

?>
<Doctype HTML>
	<html>
	<head>
	</head>
		<body>
			<p><?=$message ?></p>
			</body>
</html>