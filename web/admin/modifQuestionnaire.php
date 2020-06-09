<?php

include_once("../config.php");
if (isset($_GET["id"])){
    $id = intval($_GET["id"]);

    //On verifie que le questionnaire existe avant de faire des opérations dessus
    $sql="SELECT * FROM questionnaire WHERE id= ?";
    $select=$con->prepare($sql);
    $select->execute(array($id));
    if($select->rowCount()>0)
    {
        if (isset($_GET["insertQuestion"])){
            $insertQuestion = $_GET["insertQuestion"];
            $sql="INSERT into question (id,codeIso3) VALUES (?, ?)";
            $insert=$con->prepare($sql);
            $insert->execute([$id,$insertQuestion]);
        }
        if (isset($_GET["deleteQuestion"])){
            $insertQuestion = $_GET["deleteQuestion"];
            $sql="DELETE FROM question where id = ? and codeIso3=?";
            $insert=$con->prepare($sql);
            $insert->execute([$id,$insertQuestion]);
        }

        if (isset($_GET["deleteQuestionnaire"])){
            $sql="DELETE from questionnaire WHERE id=?";
            $update=$con->prepare($sql);
            $update->execute([$id]);
        }

    }
}

elseif (isset($_GET["add"])){
    $add = $_GET["add"];
    if (isset($_GET["nom"])){
        $nom = $_GET["nom"];
        $sql="INSERT into questionnaire (nom) VALUES (?)";
        $insert=$con->prepare($sql);
        $insert->execute([$nom]);
    }
}


$sql = "SELECT id,nom, description, (SELECT count(*) from question where question.id = questionnaire.id) as taille from questionnaire;";

$select=$con->prepare($sql);

$select->execute();
if($select->rowCount()>0)
{
    $data=$select->fetchAll(PDO::FETCH_ASSOC);
    foreach ($data as $questionnaire) {
        echo "      <li class='nav-item'>\n";
        echo "        <a class='nav-link' href='#' data-questionnaireId = '" .$questionnaire["id"]. "' data-questionnaireNom = '" .$questionnaire["nom"]. "' data-questionnaireDescription = '" .$questionnaire["description"]."'>".$questionnaire["nom"]." <span class='badge badge-light'>".$questionnaire["taille"]."</span></a>\n";
        echo "        </li>";
    }
}

?>