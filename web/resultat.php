<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Leaflet.js avec couche Stamen Watercolor</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
        
        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        
        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> 
        
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        
    </head>

    <body>
        <div class="container">
            <?php
                if (isset($_GET["points"])){
                    $point = $_GET["points"];
                    echo "<h1>Bravo! Vous avez marqué $point points </h1>";
                    if (isset($_GET["id"])){
                        echo ""; //Meilleur score
                    }
                    echo "<button type = 'button' class = 'btn btn-primary' onclick = \"location.href = 'choixQuestionnaire.php'\">choisir un autre questionnaire</button>";
                    if (isset($_GET["id"])){
                        $lienRejouer = "carte.php?id=" . $_GET["id"];
                    }
                    elseif (isset($_GET["continent"]) and isset($_GET["size"])) {
                        $lienRejouer = "carte.php?id=" . $_GET["continent"] ."&size=" . $_GET["size"];
                    }
                    else{
                        echo "bad request";
                    }
                    echo "<button type = 'button' class = 'btn btn-secondary' onclick = \"location.href = '$lienRejouer'\">Rejouer</button>";

                }
            ?>
        </div>

    </body>
</html>


