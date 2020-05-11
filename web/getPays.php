<?php

header('Content-Type: application/json');

include_once("config.php");
if (isset($_GET["iso"])){
    $iso = $_GET["iso"];

    $sql="SELECT * FROM pays WHERE codeIso3='$iso'";
    $select=$con->prepare($sql);

    $select->execute();
    if($select->rowCount()>0)
    {
        //echo"you are connected ";
        $data=$select->fetchAll(PDO::FETCH_ASSOC);
        $data[0]["drapeau"] = "images/drapeaux/$iso.svg";
        echo json_encode($data);
        
    }else 
    echo "code pays invalide";
}
