

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
            <h2>Choisissez un questionnaire</h2>
            <div class="card-columns">
                <?php
                    include_once("config.php");
                    include_once("cardQuestionnaire.php");
                    $sql="SELECT id FROM questionnaire";
                    $select=$con->prepare($sql);
                    $select->execute();
                    if($select->rowCount()>0)
                    {
                        $data=$select->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($data as $key => $value) {
                            composeCard($value["id"], $con);
                            echo "\n";
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>
    