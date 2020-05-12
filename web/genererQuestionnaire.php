<?php

header('Content-Type: application/json');

include_once("config.php");
if (isset($_GET["size"])){
    $size = $_GET["size"];

    if (isset($_GET["continent"])){
        $continent = $_GET["continent"];
        $sql="SELECT codeIso3 FROM pays WHERE continent='$continent'"; //verification du continent à faire
    }
    else{
        $sql="SELECT codeIso3 FROM pays";
    }
    $select=$con->prepare($sql);
    $select->execute();
    $paysCandidat=$select->fetchAll();
    //json_encode($paysCandidat);
    //

    $questionnaire = array();
    for ($i = 0; $i < $size; $i++) {
        $indice = array_rand($paysCandidat);//Donc peut avoir deux fois le même pays dans le questionnaire
        $codeIso3 = $paysCandidat[$indice]["codeIso3"];
        array_push($questionnaire,$codeIso3);
    }
    echo json_encode($questionnaire);

}
else{
    echo "Paramètre size non spécifié";
}
?>