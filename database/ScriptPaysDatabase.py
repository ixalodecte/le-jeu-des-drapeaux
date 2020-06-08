infoPaysFile = "../web/data/infoPays/countries.json"
contoursPath = "../web/data/contours/"
sortie = "insertPays.sql"

wikimediaUrl=""

create = "CREATE TABLE pays (codeIso3 CHAR(3) Primary key, nom VARCHAR(255) not NULL unique, continent VARCHAR(255), LienWiki varchar(500), contours varchar(1550000));"
def pretty(d, indent=0):
    for key, value in d.items():
        print('\t' * indent + str(key))
        if isinstance(value, dict):
            pretty(value, indent+1)
        else:
            print('\t' * (indent+1) + str(value))
import json

with open(infoPaysFile) as f:
    info = json.load(f)

continentCorrespondance = {
    "Americas" : "Amerique",
    "Europe" : "Europe",
    "Africa" : "Afrique",
    "Asia" : "Asie",
    "Oceania" : "Océanie",
    "Antarctic" : "Antarctique"
}

with open(sortie, 'w') as f:
    f.write("")

for e in info:
    iso3 = e["cca3"].lower()
    if (iso3 not in ["bes","cuw", "unk","ssd","sxm"]):
        nom = e["translations"]["fra"]["common"]
        lat, lon = e["latlng"]
        continent = continentCorrespondance[e["region"]]

        print(iso3)
        with open(contoursPath + iso3 + ".geo.json") as f:
            contours = str(json.load(f)["features"][0]["geometry"])

        line = "INSERT INTO pays (codeIso3, nom, continent, LienWiki, contours, lat, lon) VALUES(\"" + iso3 + "\", \"" + nom + "\", \"" + continent + "\", \"" + wikimediaUrl + nom + "\", \"" + contours + "\", " + str(lat) + ", " + str(lon) + ");"
        with open(sortie, 'a') as f:
            f.write(line + "\n\n")
