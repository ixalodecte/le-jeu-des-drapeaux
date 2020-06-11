<?php

function composeCard($id,$con){

    $sql="SELECT * FROM questionnaire where id = ?";

    $select=$con->prepare($sql);

    $select->execute(array($id));
    if($select->rowCount()>0)
    {
        $data=$select->fetch(PDO::FETCH_ASSOC);
        $ls =array();
        echo "<div class=\"card shadow\">\n";
        echo "<img class=\"card-img-top\" src=\"/images/questionnaire/" . $data["image"] . "\" alt=\"Card image\" style=\"width:100%\">\n";
        echo "<div class=\"card-body\">\n";
        echo "<h4 class=\"card-title\">". $data["nom"] ."</h4>\n";
        echo "<p class=\"card-text\">" . $data["description"]. "</p>\n";
        echo "<a href=\"carte.php?id=". $data["id"] ."\" class=\"btn btn-primary\"> Jouer </a>\n";
        echo "</div>\n";
        echo "</div>\n";
        
    }
}
?>