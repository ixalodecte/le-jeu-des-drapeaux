<!DOCTYPE html>
<head>
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


<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>
<body>

<div class="d-flex p-3">
<!-- La liste des questionnaire -->
<div class="container scrolable p-2" id="questionnaire">
    <h2 id="choixQuestionnaire">Choisissez un questionnaire</h2>
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
<div class = "container scrolable p-2">
    <table class='table table-hover' id='tableQuestionnaire'>
        <thead>
            <tr>
                <h2 id="titreQuestionnaire">Choisissez un questionnaire <button id="proposerSupprimerQuestionnaire" type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal" disabled>Supprimer</button></h2>
            </tr>

            <!-- formulaire ajout d'un pays-->
            <tr>
                <div class="input-group mb-3 ">
                    <b class="mb-2 mr-sm-2">ajouter un pays:  </b>
                    <input class="form-control" type="search" list="pays" id="nomPays" placeholder="nom du pays" name="nom" disabled>
                    <div class="input-group-append">
                        <button class="btn btn-success" id="sendQuestion" >Ajouter</button>
                    </div>
                </div>
            </tr>

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

<div class="modal" id="myModal">
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

            $("#titreQuestionnaire").text($(this).data("questionnairenom"));

            $("#nomPays").prop("disabled",false);
            $("#proposerSupprimerQuestionnaire").prop("disabled",false);

            $('.nav li a.active').removeClass('active');
            $(this).addClass('active');
            
            id = $(this).data("questionnaireid");
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