var questionnaire;         // Contient une liste de code iso3 (pays du questionnaire)
var polygonePays=-1;       // Objet qui represente le polygone du pays sur la carte
var pointTotal = 0;        // Points de la partie
var nombreDeQuestion = 0;  // Nombre de question du questionnaire
var avancement = 0;        // Incrémenté de 1 à chaque nouvelle question
var essai = 0;             // Nombre de clique sur la carte, remis à 0 à chaque nouvelle question
var essaiMax= 10;          // Nombre de clique maximum autorisés
var fini = false;          // Mis a "true" quand l'utilisateur clique sur le pays (ou que son nombre d'essai est écoulé)
var pointQuestion = 0;     // Nombre de point gagner après avoir répondu juste
var pays ={};              // Objet qui represente les infos sur le pays de la question courante.
var id = -1;
var continent = "";
var questionnaireFini = false;


function nouvelleQuestion (){
    avancement += 1;
    essai = 0;
    fini = false;
    pointQuestion = 0;
    updateProgressBar(avancement, nombreDeQuestion);
    
    
    if (avancement>1) {
        map.removeLayer(polygonePays);
    }
    if (avancement <= nombreDeQuestion){

        $("#message").text("_");
        $("#incrementPoint").text(" ");
        $("#compteurPoint").text(pointTotal);
        $("#infoPays").css("visibility", "hidden");

        pays = {};
        getPays(questionnaire[avancement-1]); //
        //Récupération contours du pays

                
        document.getElementById("boutonInfo").disabled = true;
        document.getElementById("boutonSuivant").disabled = true;
        
    }
    if (avancement == nombreDeQuestion){
        derniereQuestion();
    }
}

function getPays(iso3) {
    var xhttp;
    if (iso3 == "") {
        return;
    }
    $.ajax({
        url: "getPays.php?iso="+iso3,
        type: 'GET',
        cache: false,
        timeout: 30000,
    }).done(function(msg) {
        pays = msg;
        console.log(msg)
        pays["contours"] = JSON.parse(pays["contours"].split("'").join('"'));
        console.log(pays);
        //dict = JSON.parse(msg);
        updateQuestion(pays);
      });

}

function updateQuestion(pays){
    //Page wikipedia dans le champs "info"
    // !!! Faire ça en back-end?
    $("#modalInfoTitre").text(pays["nom"]);
    $.getJSON(pays["lienApiWiki"], function(data){
        $("#infoDescription").text(data["extract"] + " (source : wikipédia)")
        
    });
    $("#lienWikipedia").attr("href", pays["LienWiki"]);
    console.log(pays);
    $("#infoTitre").text(pays["nomPays"]);
    $("#drapeau").attr("src",pays["drapeau"]);

    polygonePays = L.geoJSON(pays["contours"], {style:{color:"green", fillOpacity: 0, opacity:0, cursor:"crosshair"}}).addTo(map); //On place le plolygone, invisible pour l'instant
    polygonePays.on("click", onCountryClick)
    $(".leaflet-interactive").attr("style","cursor:crosshair"); // On change le curseur pour qu'il soit pareil que la map. (change de place ?)

}


function derniereQuestion(){
    $("#boutonSuivant").text("finir");
    $("#boutonSuivant").click(fin);
    questionnaireFini = true;
}

function updateProgressBar (avancement, nombreDeQuestion){
    $(".progress-bar").css("width", (100/nombreDeQuestion)*(avancement) + "%");
}

function calculPoint (nbEssai){
    return Math.floor(1000/nbEssai);
}
function fin(){
    $('#modalFinTitre').text("Bravo! Vous avez gagné " + pointTotal + " points.");
    if (id != -1){
        $("#modalFinRejouer").attr("href", "carte.php?id=" + id);
    }
    else{
        $("#modalFinRejouer").attr("href","carte.php?continent=" + continent + "&size=" + nombreDeQuestion);
    }
    $('#modalFin').modal('show')
}


// Fonction qui réagit au clic sur la carte (e contiendra les données liées au clic)
function onMapClick(e) {
    if (!fini){
        essai += 1
        if (essai>=essaiMax){
            fini = true;
            finQuestion(false);
        }
        else{
            $("#message").text("Essai n°" + essai+ ", vous êtes à " + Math.floor(distCountry(pays["contours"], e.latlng.lat, e.latlng.lng))+ "km");
        }
    }
    //$("#message").
}

// Fonction qui réagit au clic sur un pays (e contiendra les données liées au clic)
function onCountryClick(e){
    if (!fini){
        essai +=1
        fini = true;
        finQuestion(true);
        //On active les boutons "question suivante" et "info sur le pays"

    }
}

function finQuestion(succes){
    polygonePays.setStyle({fillOpacity: 0.3, opacity:0.5}); //On affiche les contours
    

    if (succes){
        console.log("okkk");
        $("#message").text("Essai n°" + (essai) + " : Bravo, vous avez trouvé!");
        pointQuestion = calculPoint(essai);
        $("#incrementPoint").text(" + " + pointQuestion);
        pointTotal += pointQuestion;
    }
    else{
        $("#message").text("Dommage");
        polygonePays.setStyle({color:"red"}); //On affiche les contours
        map.setView([pays.lat, pays.lon]); //Zoom sur le pays
    }

    document.getElementById("boutonInfo").disabled = false;
    document.getElementById("boutonSuivant").disabled = false;
}

function distance(lat1, lon1, lat2, lon2) { //lien : https://www.geodatasource.com/developers/javascript
    if ((lat1 == lat2) && (lon1 == lon2)) {
        return 0;
    }
    else {
        var radlat1 = Math.PI * lat1/180;
        var radlat2 = Math.PI * lat2/180;
        var theta = lon1-lon2;
        var radtheta = Math.PI * theta/180;
        var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        if (dist > 1) {
            dist = 1;
        }
        dist = Math.acos(dist);
        dist = dist * 180/Math.PI;
        dist = dist * 60 * 1.1515;
        dist = dist * 1.609344
        return dist;
    }
}

function getPoints(contours){
    var points = []
    if (contours.type == "Polygon"){
        for (p of contours.coordinates){
            for (point of p){
                points.push([point[1],point[0]]);
            }
        }
    }
    else{
        for (p of contours.coordinates){
            for (pol of p){
                for (point of pol){
                    points.push([point[1],point[0]]);
                    //console.log(point);
                }
            }
        }
    }
    return points;
}

function distCountry(contours, lat, lon){
    distances = [];
    for (point of getPoints(contours)){
        distances.push(distance(lat, lon, point[0],point[1]));
    }

    return (Math.min.apply(null, distances));
}

function centrage(contours){
    
}