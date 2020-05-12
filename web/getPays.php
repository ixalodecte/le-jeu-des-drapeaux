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
        $data=$select->fetch(PDO::FETCH_ASSOC);
        $data["drapeau"] = "images/drapeaux/$iso.svg";
        $data = array_map('utf8_encode' ,$data);
        echo json_encode($data);
        
    }else 
    echo "code pays invalide";
}
?>