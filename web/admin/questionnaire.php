<?php
    session_start();
    include_once("../config.php");
    $dossierDestination = "../images/questionnaire/";
    $fichierDestination = $dossierDestination . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;

    if (isset($_POST["idQuestionnaire"])){
        $id = $_POST["idQuestionnaire"];
        if(isset($_POST["submit"]) and isset($_FILES["fileToUpload"])) {
            
            // Vérifie que le nom n'est pas déja pris
            if (file_exists($fichierDestination)) {
                $uploadOk = 0;
            }
            
            if ($uploadOk == 1){
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $fichierDestination)) {
                    //On supprime l'image précedente du serveur
                    $sql="SELECT image from questionnaire WHERE id=?";
                    $select=$con->prepare($sql);
                    $select->execute([$id]);
                    if($select->rowCount()>0)
                    {
                        $data=$select->fetch(PDO::FETCH_ASSOC);
                        $image = $data["image"];
                        if ($image !== "pas_image.jpg"){
                            unlink("../images/questionnaire/" . $image);
                        }                        
                    }

                    $sql="UPDATE questionnaire set image=? WHERE id=?";
                    $update=$con->prepare($sql);
                    $update->execute([$_FILES["fileToUpload"]["name"],$id]);
                }
            }
        }

        if (isset($_POST["updateNom"])){
            $nom = $_POST["updateNom"];
            $sql="UPDATE questionnaire set nom=? WHERE id=?";
            $update=$con->prepare($sql);
            $update->execute([$nom,$id]);
        }
        if (isset($_POST["updateDescription"])){
            $description = $_POST["updateDescription"];
            $sql="UPDATE questionnaire set description=? WHERE id=?";
            $update=$con->prepare($sql);
            $update->execute([$description,$id]);
        }
    }
?>
<!DOCTYPE html>
<head>

<title> gestion des questionnaire </title>
<meta charset="utf-8">

    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
        <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> 
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    



</head>
<body>
<?php include_once("../navBar.php");?>

    <div class="mt-3">
        <h2 id="titreQuestionnaire" class="text-center mr-3">
            Choisissez un questionnaire
            <button type="button" id = "proposerModifierQuestionnaire" class="btn btn-primary mr-2" data-toggle="collapse" data-target="#collapse_modif" disabled>
                Modifier
            </button>
            <button id="proposerSupprimerQuestionnaire" type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalSupprimer" disabled>
                Supprimer
            </button>
        </h2>
    </div>

    <!-- Pour modifier un questionnaire existant -->
    <div id="collapse_modif" class="collapse">
        <div class="container shadow p-2">
            <!-- Form, contient:
                nom du questionnaire (updateNom)
                description du questionnaire (nouvelleQuestion)
                id du questionnaire à modifier (idQuestionnaireUpload)
                image d'illustration (fileToUpload)
             -->
            <form action="questionnaire.php" method="post" enctype="multipart/form-data">
                <div class="form-group mb-3 mr-2">
                    <label for="nouvelleDescription">modifier le nom du questionnaire : </label>
                        <input class="form-control" id="nouveauNom" placeholder="nouveau nom" name="updateNom">
                    </div>

                    <div class="form-group">
                        <label for="nouvelleDescription">Description :</label>
                        <textarea class="form-control" rows="3" id="nouvelleDescription" name="updateDescription"></textarea>
                    </div>

                    <!-- Champ modifié avec javascript -->
                    <input type = "hidden" name = "idQuestionnaire" id="idQuestionnaireUpload">

                    <div class="custom-file mb-2">
                        <input type="file" class="custom-file-input" id="customFile" name="fileToUpload">
                        <label class="custom-file-label" for="customFile">Choisissez une image</label>
                    </div>

                    <button type="submit" class="btn btn-primary" id="modifierImage" name = "submit">Modifier</button>
            </form>
        </div>
    </div>

<div class="d-flex p-3">
    <!-- La liste des questionnaire -->
    <div class="container shadow p-5 mr-3" id="questionnaire">

        <!-- Input pour ajouter un questionnaire -->
        <div class="input-group mb-3">
            <b class="mb-2 mr-sm-2">ajouter un questionnaire:  </b>
            <input class="form-control" id="nomQuestionnaire" placeholder="nom du questionnaire" name="nom">
            <div class="input-group-append">
                <button type="submit" class="btn btn-success" id="submitNomQuestionnaire">Ajouter</button>
            </div>
        </div>

        <!-- La liste des questionnaire -->
        <ul class="nav nav-pills nav-stacked" id ="listeQuestionnaire">
            <?php
                echo file_get_contents('http://127.0.0.1/questionnaire/modifQuestionnaire.php');
            ?>
        </ul>
    </div >

    <!-- La liste des pays dans un questionnaire -->
    <div class = "container shadow p-5">

        <!-- Input pour ajouter un pays -->
        <div class="input-group mb-3 ">
            <b class="mb-2 mr-sm-2">ajouter un pays:  </b>
            <input class="form-control" type="search" list="pays" id="nomPays" placeholder="nom du pays" name="nom" disabled>
            <div class="input-group-append">
                <button class="btn btn-success" id="sendQuestion" >Ajouter</button>
            </div>
        </div>

        <table class='table table-hover'>
            <thead>
                <tr>
                    <th>nom</th>
                    <th>iso</th>
                    <th>continent</th>
                </tr>
            </thead>

            <!-- Représente les pays dans le questionnaire -->
            <tbody id=rowQuestionnaire></tbody>
        </table>
    </div>
</div>

<!-- modal pour la suppression -->
<div class="modal" id="modalSupprimer">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Confirmation de la suppression</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Voulez vous vraiment supprimer ce questionnaire? Cette action est irreversible.
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button id="supprimerQuestionnaire" type="button" class="btn btn-primary" data-dismiss="modal">Confirmer</button>
            </div>
        </div>
    </div>
</div>

<script>
    var id; // Represente l'id du questionnaire selectionné

    $(document).ready(function(){
        // Pour avoir le nom du fichier dans le file input après selection
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        // requete ajax pour actualiser la liste des questionnaire après un ajout de questionnaire
        $("#submitNomQuestionnaire").click(function(){
            text=$("#nomQuestionnaire").val();
            $.get("modifQuestionnaire.php", {add:"", nom: text}, function( data ) {
                $("#listeQuestionnaire").html(data);
                setClickQuestionnaire();
            });
        });

        // requete ajax pour actualiser la liste des questionnaire après une suppression de questionnaire
        $("#supprimerQuestionnaire").click(function(){
            $.get("modifQuestionnaire.php", {deleteQuestionnaire:"", id: id}, function( data ) {
                $("#listeQuestionnaire").html(data);
                location.reload(true);
            });
        });

        // Requete ajax pour ajouter une question à un questionnaire
        $('#sendQuestion').on('click', function () {
            var text = $("#nomPays").val();
            if (text in correspondance){
                iso3 = correspondance[text]
                $.ajax({
                    url: "modifQuestionnaire.php",
                    data: {id:id, insertQuestion:iso3},
                    type: 'GET',
                    cache: false,
                    timeout: 30000,
                }).done(function(msg) {
                    $("#listeQuestionnaire").html(msg);
                    setClickQuestionnaire();
                    remplirTableauQuestion(id);
                });
            }
        });

        //Gestion des cliques sur les lignes representant les questionnaires
        setClickQuestionnaire();

    });

    

    // Appelé quand l'utilisateur selectionne un questionnaire
    function setClickQuestionnaire(){
        $(".nav-link").click(function(e) {
            e.preventDefault();

            //Récupération des données du questionnaire
            nomQuestionnaire = $(this).data("questionnairenom");
            descriptionQuestionnaire = $(this).data("questionnairedescription");

            //Remplace le titre en haut par le nom du questionnaire
            document.getElementById("titreQuestionnaire").childNodes[0].textContent = (nomQuestionnaire) + " ";

            // Prerempli les champs dans la section modifier
            $("#nouvelleDescription").val(descriptionQuestionnaire);
            nouveauNom = $("#nouveauNom").val(nomQuestionnaire);

            $("#nomPays").prop("disabled",false);
            $("#proposerSupprimerQuestionnaire").prop("disabled",false);
            $("#proposerModifierQuestionnaire").prop("disabled",false);

            // Pour metre en bleu la cellule du questionnaire selectionné
            $('.nav li a.active').removeClass('active');
            $(this).addClass('active');
            
            id = $(this).data("questionnaireid");
            $("#idQuestionnaireUpload").val(id);

            remplirTableauQuestion(id);            
        });
    }

    // Rempli le tableau avec la liste des pays qui sont dans le questionnaire passé en parametre
    function remplirTableauQuestion(id){
        $.getJSON("/questionnaire/getQuestionnaire.php", {full : "", id : id}, function( data ) {
            var html = "";
            for (p of data){
                html += "<tr>";
                html += "<td><button type='button' class='close' onclick=\"deleteRow('" + p.codeIso3 + "')\">&times;</button>" + p.nom + "</td>";
                html += "<td>" + p.codeIso3 + "</td>";
                html += "<td>" + p.continent + "</td>";
                html += "</tr>"
            }
            $("#rowQuestionnaire").html(html);
        });
    }

    // Appelée quand l'utilisateur supprime un pays
    function deleteRow(iso){
        $("#listeQuestionnaire").load("modifQuestionnaire.php?id=" + id + "&deleteQuestion=" + iso, function(){
            setClickQuestionnaire();
            remplirTableauQuestion(id);
        });
    }
</script>

<?php
//Création de la "datalist" (pour l'autocomplétion des pays)

    $json = file_get_contents('http://127.0.0.1/getPays.php?all');
    $allPays = json_decode($json);
    $correspondance = array(); // La correspondance entre nom de pays et iso3
    echo "\n\n";
    echo "<datalist id='pays'>"."\n";
    foreach ($allPays as $pays) {
        $iso = $pays->codeIso3;
        $nom = $pays->nom;
        $correspondance[$nom] = $iso;
        echo "    <option value='".htmlspecialchars($nom, ENT_QUOTES, 'UTF-8')."'>"."\n";
    }
    echo "</datalist>"."\n";
    echo "<script>";
    echo "var correspondance = ".json_encode($correspondance);
    echo "</script>";

?>



</body>
</html>


