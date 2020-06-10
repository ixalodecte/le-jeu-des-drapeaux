<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Leaflet.js avec couche Stamen Watercolor</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
        
        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
                <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> 
        <style>
            
            p {
                font-size: x-large;
                padding-left: 10px;
            }
        </style>
        
    </head>


    <body>
    <div class="container shadow">
        
        <!-- La barre de progression (avancement du questionnaire)-->
        <div class="progress mb-3 mt-3">
            <div class="progress-bar" style="width:20%"></div>
        </div>
        
        <!-- "Tableau de bord" du questionnaire -->
        <div class="row bg-light text-dark">

            <!-- Nom du questionnaire -->
            <div class="col-3" id="nomQuestionnaire"><p>Questionnaire : Monde Entier</p></div>
            
            <!-- Compteur de points -->
            <div class="col-3"><p>Nombre de points : </p> <p> <span  id="compteurPoint">0</span>  <span  id="incrementPoint" style="color: forestgreen;"></span> </p></div>
            
            <!-- Boutons pour passer à la question suivante + infos sur le pays -->
            <div id = "boutonInfoSuivant" class="col-4">
                <p id = "message">_</p>
                <button id = "boutonInfo" type ="button" class="btn btn-info mr-3" data-toggle="modal" data-target="#modalInfo" disabled>
                    info
                </button>
                <button id = "boutonSuivant" type ="button" class="btn btn-primary" disabled>
                    suivant
                </button>
                
                <!-- Modal info sur le pays -->
                <div class="modal fade" id="modalInfo">
                    <div class="modal-dialog">
                        <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 id="modalInfoTitre" class="infoTitre">Pays</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <p id="infoDescription" style="font-size: medium;">Some example text some example text. John Doe is an architect and engineer</p>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <a id="lienWikipedia" href="#" target="_blank" class="btn btn-info">wikipedia</a>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>

                        </div>
                    </div>
                </div>

                <!-- Modal ouverte quand on fini le questionnaire -->
                <div class="modal fade" id="modalFin">
                    <div class="modal-dialog">
                        <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 id="modalFinTitre" class="infoTitre">Bravo, vous avez gagné points</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <p id="modalFinDescription" style="font-size: medium;"></p>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <a id="lienWikipedia" href="/jouer/choixQuestionnaire.php" class="btn btn-info">Choisir un autre questionnaire</a>
                            <a class="btn btn-info" href="#" id="modalFinRejouer">Rejouer</a>
                        </div>

                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Drapeau du pays à trouver -->
            <div class="col-2">
                <img id="drapeau" src="#" alt="Trouver ce drapeau" width="140" class="border">
            </div>
        </div>
        
            
        <!-- La Carte -->
        <div id="maDiv" style="width: 100%; height: 500px"></div>
        </div>
        <!-- Script pour la carte -->
        <script>
            // bornes pour empecher la carte StamenWatercolor de "dériver" trop loin...
            var northWest = L.latLng(90, -180);
            var southEast = L.latLng(-90, 180);
            var bornes = L.latLngBounds(northWest, southEast);
            // Initialisation de la couche StamenWatercolor
            var coucheStamenWatercolor = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.{ext}', {
                attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                subdomains: 'abcd',
                ext: 'jpg'
            });
            // Initialisation de la carte et association avec la div
            var map = new L.Map('maDiv', {
                center: [48.858376, 2.294442],
                minZoom: 2,
                maxZoom: 18,
                zoom: 5,
                maxBounds: bornes
            });
            //var map = L.map('maDiv').setView([48.858376, 2.294442],5);
            // Affichage de la carte
            map.addLayer(coucheStamenWatercolor);
            // Juste pour changer la forme du curseur par défaut de la souris
            document.getElementById('maDiv').style.cursor = 'crosshair'
            //map.fitBounds(bornes);
            // Initilisation d'un popup
            var popup = L.popup();
            // Fonction de conversion au format GeoJSON
            function coordGeoJSON(latlng,precision) { 
                return '[' +
                L.Util.formatNum(latlng.lng, precision) + ',' +
                L.Util.formatNum(latlng.lat, precision) + ']';
            }

            
            $(document).ready(function(){
                <?php
                    if (isset($_GET["id"])){
                        $id = $_GET["id"];
                        if (ctype_digit($id)){
                            echo "id=" . $id . "\n";
                            $lienQuestionnaire = "/questionnaire/getQuestionnaire.php?id=" . $id;
                        }
                    }
                    elseif (isset($_GET["size"]) and isset($_GET["continent"])){
                        $size = $_GET["size"];
                        $continent = $_GET["continent"];
                        //Verification des paramètres par sécurité
                        if (ctype_digit($size) and in_array($continent, ["Europe", "Afrique", "Asie", "Océanie", "Antarctique", "Amerique"], true)){
                            echo "continent='" . $continent . "'\n";
                            $lienQuestionnaire = "/questionnaire/genererQuestionnaire.php?size=" . $size . "&continent=" . $continent;

                        }
                    }
                    else {
                        echo "coucou";
                    }
                    echo "questionnaire = " . file_get_contents('http://127.0.0.1' . $lienQuestionnaire) . "\n";
                ?>
                nombreDeQuestion = questionnaire.length;
                nouvelleQuestion();
                map.on('click', onMapClick);
                // Association Evenement/Fonction handler
                $("#boutonSuivant").click(nouvelleQuestion);

            });
                
        </script>

        <script src="/script/carte.js"></script>
    </body>
</html>
    