var questionnaire;
var polygonePays=-1;
var pointTotal = 0;
var nombreDeQuestion = 3;
var avancement = 0;
var essai = 0;
var succes = false;
var pointQuestion = 0;
var pays;

function nouvelleQuestion (){
    avancement += 1;
    essai = 0;
    succes = false;
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
        getPays("afg"); //questionnaire[avancement-1]
        //Récupération contours du pays

                
        document.getElementById("boutonInfo").disabled = true;
        document.getElementById("boutonSuivant").disabled = true;
        
    }
    else{
        fin();
    }
}

function getPays(iso3) {
    var xhttp;
    if (iso3 == "") {
        return;
    }
    var dict;
    $.ajax({
        url: "getPays.php?iso="+iso3,
        type: 'GET',
        async: false,
        cache: false,
        timeout: 30000,
        error: function(){
            return true;
        },
        success: function(msg){
            pays = msg[0]
            pays["contours"] = JSON.parse(pays["contours"].split("'").join('"'));
            console.log(pays);
            //dict = JSON.parse(msg);
            updateQuestion(pays);
        }
    });
    return dict;

}

function updateQuestion(pays){
    //Page wikipedia dans le champs "info"
    $.getJSON(pays["LienWiki"], function(data){
        $("#infoDescription").text(data["extract"])
        
    });
    $("#infoTitre").text(pays["nomPays"]);
    $("#drapeau").attr("src",pays["drapeau"]);

    polygonePays = L.geoJSON(pays["contours"], {style:{color:"green", fillOpacity: 0, opacity:0, cursor:"crosshair"}}).addTo(map); //On place le plolygone, invisible pour l'instant
    polygonePays.on("click", onCountryClick)
    $(".leaflet-interactive").attr("style","cursor:crosshair"); // On change le curseur pour qu'il soit pareil que la map. (change de place ?)

}


function fin(){
    $("#message").text("Questionnaire fini");
    $("#incrementPoint").text(" ");
    $("#compteurPoint").text(pointTotal);
}

function updateProgressBar (avancement, nombreDeQuestion){
    $(".progress-bar").css("width", (100/nombreDeQuestion)*(avancement-1) + "%");
}

function calculPoint (nbEssai){
    return Math.floor(1000/nbEssai);
}


// Fonction qui réagit au clic sur la carte (e contiendra les données liées au clic)
function onMapClick(e) {
    if (!succes){
        essai += 1
        $("#message").text("Essai n°" + essai+ ", vous êtes à " + Math.floor(distCountry(pays["contours"], e.latlng.lat, e.latlng.lng))+ "km");
        
    }
    //$("#message").
}

// Fonction qui réagit au clic sur un pays (e contiendra les données liées au clic)
function onCountryClick(e){
    if (!succes){
        essai +=1
        polygonePays.setStyle({fillOpacity: 0.3, opacity:0.5}); //On affiche les contours
        //map.setView(centrePays,5); //Zoom sur le pays
        $("#message").text("Essai n°" + (essai) + " : Bravo, vous avez trouvé!");
        pointQuestion = calculPoint(essai);
        $("#incrementPoint").text(" + " + pointQuestion);
        pointTotal += pointQuestion;
        
        succes = true;
        
        //On active les boutons "question suivante" et "info sur le pays"
        document.getElementById("boutonInfo").disabled = false;
        document.getElementById("boutonSuivant").disabled = false;
    }
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

function distCountry(contours, lat, lon){
    var points = []
    for (point of contours.coordinates[0]){
        points.push(distance(lat, lon, point[1],point[0]));
    }
    return (Math.min.apply(null, points));

}

//Pas sur de garder ça
dragElement(document.getElementById("infoPays"));

function dragElement(elmnt) {  //source : https://www.w3schools.com/howto/howto_js_draggable.asp
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    if (document.getElementById(elmnt.id + "header")) {
        // if present, the header is where you move the DIV from:
        document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
    } else {
        // otherwise, move the DIV from anywhere inside the DIV:
        elmnt.onmousedown = dragMouseDown;
    }
    
    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
    }
    
    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }
    function closeDragElement() {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

$(document).ready(function(){
    $.getJSON('https://raw.githubusercontent.com/leodechaumet/le-jeu-des-drapeaux/master/web/questionnaire.json', function(data) {
        questionnaire = data;
        nombreDeQuestion = questionnaire.properties.size;
        nombreDeQuestion = 3
        nouvelleQuestion()
        
        
    });
    
    // Association Evenement/Fonction handler
    map.on('click', onMapClick);
    $("#boutonSuivant").click(nouvelleQuestion);
    
});

