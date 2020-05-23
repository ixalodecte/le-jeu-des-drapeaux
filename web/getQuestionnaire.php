<?php

header('Content-Type: application/json');

include_once("config.php");
if (isset($_GET["id"])){
    $id = $_GET["id"];

    if (isset($_GET["full"])){
        $sql="SELECT question.id, codeIso3, nom, continent FROM question JOIN pays USING (codeIso3) WHERE question.id= ?";
        $select=$con->prepare($sql);
    
        $select->execute(array($id));
        if($select->rowCount()>0)
        {
            $data=$select->fetchAll(PDO::FETCH_ASSOC);
            foreach ($data as $key => $value) {
                $data[$key] = array_map('utf8_encode' ,$value);
            }
            
            echo json_encode($data);
        }
        else{
            $data = array();
            echo json_encode($data);
        }
    }
    else{
        $sql="SELECT * FROM question WHERE id= ?";
        $select=$con->prepare($sql);
    
        $select->execute(array($id));
        if($select->rowCount()>0)
        {
            $data=$select->fetchAll(PDO::FETCH_ASSOC);
            $ls =array();
    
            foreach ($data as $value) {
                array_push($ls, $value["codeIso3"]);
            }
            echo json_encode($ls);
            
        }else{
            $data = array();
            echo json_encode($data);
        }
    }


}
?>