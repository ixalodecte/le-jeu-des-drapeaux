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
    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>
<body>

<div class="container scrolable" id="questionnaire">
    <div class="input-group mb-3">

        <label for="nomQuestionnaire">ajouter un questionnaire:  </label>
        <input class="form-control" id="nomQuestionnaire" placeholder="nom du questionnaire" name="nom">
        <input type="hidden" name="add">
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary" id="submitNomQuestionnaire">Submit</button>
        </div>
    </div>


    <script>

        $(document).ready(function(){
            $("#submitNomQuestionnaire").click(function(){
                text=$("#nomQuestionnaire").val();
                console.log(text);
                $("#listeQuestionnaire").load("modifQuestionnaire.php?add&nom=" + text);
            });
        });
    </script>

    <ul class="nav nav-pills nav-stacked" id ="listeQuestionnaire">
        <?php
            echo file_get_contents('http://127.0.0.1/modifQuestionnaire.php');
        ?>
    </ul>

</div >




<div class = "container scrolable">
    <div class="input-group mb-3">
        <label for="nomQuestionnaire">ajouter un pays:  </label>
        <input class="form-control" type="search" list="pays" id="nomPays" placeholder="nom du pays" name="nom">
        <div class="input-group-append">
            <button class="btn btn-primary" id="sendQuestion" >Submit</button>
        </div>
    </div>




    <table class='table table-hover' id='tableQuestionnaire'>
        <thead>
            <tr>
                <th>nom</th>
                <th>iso</th>
                <th>continent</th>
        </tr>
        </thead>
        <tbody id=rowQuestionnaire></tbody>
    </table>
</div>

<script>
    var id;
    
    //Gestion des cliques sur les lignes representant les questionnaires
    $(document).ready(function($) {
        $(".nav-link").click(function(e) {
            e.preventDefault();
            id = $(this).data("questionnaireid");

            $('.nav li a.active').removeClass('active');
            $(this).addClass('active');
            
            console.log(id);
            remplirTableauQuestion(id);            
        });
    });

    function remplirTableauQuestion(id){
        var lien = "getQuestionnaire.php?full&id=" + id;
        $.getJSON(lien, function( data ) {
            console.log(data);
            var html = "";
            for (p of data){
                html += "<tr>";
                html += "<td>" + p.nom + "</td>";
                html += "<td>" + p.codeIso3 + "</td>";
                html += "<td>" + p.continent + "</td>";
            }
            $("#rowQuestionnaire").html(html);
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