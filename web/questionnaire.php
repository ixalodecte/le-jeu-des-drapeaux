<!DOCTYPE html>
<head>

<title> gestion des questionnaire </title>
<meta charset="utf-8">

    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> 


</head>
<body>
<h2 id="titreQuestionnaire" class="text-center mr-3">Choisissez un questionnaire<button type="button" id = "proposerModifierQuestionnaire" class="btn btn-primary" data-toggle="collapse" data-target="#demo" disabled>Modifier</button><button id="proposerSupprimerQuestionnaire" type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalSupprimer" disabled>Supprimer</button></h2>
    <div id="demo" class="collapse">
        <div class="container p-2">

            <form action="questionnaire.php" method="post" enctype="multipart/form-data">
                <div class="form-group mb-3 mr-2">
                    <b class="mb-2 mr-sm-2">modifier le nom du questionnaire : </b>
                    <input class="form-control" id="nouveauNom" placeholder="nouveau nom" name="updateNom">

                </div>
                <div class="form-group">
                    <label for="nouvelleDescription">Description</label>
                    <textarea class="form-control" rows="3" id="nouvelleDescription" name="updateDescription"></textarea>
                </div> 
                <input type = "hidden" name = "idQuestionnaire" id="idQuestionnaireUpload">

                <div class="input-group mb-3 mr-2">

                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="fileToUpload">
                        <label class="custom-file-label" for="customFile">Choisissez une image</label>

                    </div>

                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary" id="modifierImage" name = "submit">Modifier</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<div class="d-flex p-3">
<!-- La liste des questionnaire -->
<div class="container  p-2" id="questionnaire">
    <div class="input-group mb-3">


        <b class="mb-2 mr-sm-2">ajouter un questionnaire:  </b>
        <input class="form-control" id="nomQuestionnaire" placeholder="nom du questionnaire" name="nom">
        <div class="input-group-append">
            <button type="submit" class="btn btn-success" id="submitNomQuestionnaire">Ajouter</button>
        </div>
    </div>

    <ul class="nav nav-pills nav-stacked" id ="listeQuestionnaire">
        <?php
            echo file_get_contents('http://127.0.0.1/modifQuestionnaire.php');
        ?>
    </ul>
</div >

<!-- La liste des pays dans un questionnaire -->
<div class = "container  p-2">

    <div class="input-group mb-3 ">
                    <b class="mb-2 mr-sm-2">ajouter un pays:  </b>
                    <input class="form-control" type="search" list="pays" id="nomPays" placeholder="nom du pays" name="nom" disabled>
                    <div class="input-group-append">
                        <button class="btn btn-success" id="sendQuestion" >Ajouter</button>
                    </div>
                </div>
    <table class='table table-hover' id='tableQuestionnaire'>
        <thead>

            <!-- formulaire ajout d'un pays-->

            <tr>
                <th>nom</th>
                <th>iso</th>
                <th>continent</th>
            </tr>
        </thead>
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

    $(document).ready(function(){
            $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        $("#submitNomQuestionnaire").click(function(){
            text=$("#nomQuestionnaire").val();
            console.log(text);
            $("#listeQuestionnaire").load("modifQuestionnaire.php?add&nom=" + text, setClickQuestionnaire);
            
        });
        $("#supprimerQuestionnaire").click(function(){
            $("#listeQuestionnaire").load("modifQuestionnaire.php?deleteQuestionnaire&id=" + id, function(){location.reload(true); });
            
        });

    });
    var id;
    
    //Gestion des cliques sur les lignes representant les questionnaires
    $(document).ready(function($) {
        setClickQuestionnaire()
    });

    function setClickQuestionnaire(){
        $(".nav-link").click(function(e) {
            e.preventDefault();
            nomQuestionnaire = $(this).data("questionnairenom");
            descriptionQuestionnaire = $(this).data("questionnairedescription");


            document.getElementById("titreQuestionnaire").childNodes[0].textContent = (nomQuestionnaire) + " ";
            $("#nouvelleDescription").val(descriptionQuestionnaire);
            nouveauNom = $("#nouveauNom").val(nomQuestionnaire);

            $("#nomPays").prop("disabled",false);
            $("#proposerSupprimerQuestionnaire").prop("disabled",false);
            $("#proposerModifierQuestionnaire").prop("disabled",false);

            $('.nav li a.active').removeClass('active');
            $(this).addClass('active');
            
            id = $(this).data("questionnaireid");
            $("#idQuestionnaireUpload").val(id);
            console.log(id);
            remplirTableauQuestion(id);            
        });
    }

    function remplirTableauQuestion(id){
        var lien = "getQuestionnaire.php?full&id=" + id;
        $.getJSON(lien, function( data ) {
            console.log(data);
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

    function deleteRow(iso){
        $("#listeQuestionnaire").load("modifQuestionnaire.php?id=" + id + "&deleteQuestion=" + iso, function(){
            setClickQuestionnaire();
            remplirTableauQuestion(id);
        });
    }

    $('#sendQuestion').on('click', function () {
        var text = $("#nomPays").val();
        if (text in correspondance){
            iso3 = correspondance[text]
            $.ajax({
                url: "modifQuestionnaire.php?id=" + id + "&insertQuestion="+iso3,
                type: 'GET',
                cache: false,
                timeout: 30000,
            }).done(function(msg) {
                $("#listeQuestionnaire").html(msg);
                setClickQuestionnaire();
                remplirTableauQuestion(id);
            });
        }
        else{
            console.log("non");
        }
    });
</script>

<?php

    //Création de la "datalist" (pour l'autocomplétion des pays)
    $json = file_get_contents('http://127.0.0.1/getPays.php?all');
    $allPays = json_decode($json);
    $correspondance = array();
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

<?php
include_once("config.php");
$target_dir = "images/questionnaire/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if (isset($_POST["idQuestionnaire"])){
    $id = $_POST["idQuestionnaire"];
    if(isset($_POST["submit"]) and isset($_FILES["fileToUpload"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }
    
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $sql="UPDATE questionnaire set image=? WHERE id=?";
            $update=$con->prepare($sql);
            $update->execute([$_FILES["fileToUpload"]["name"],$id]);
        echo "";
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
