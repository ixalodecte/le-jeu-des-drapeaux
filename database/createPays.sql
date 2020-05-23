drop table pays;
CREATE TABLE pays (
    id int Primary key AUTO_INCREMENT,
    codeIso3 CHAR(3) not NULL unique,
    nom VARCHAR(255) not NULL unique,
    continent VARCHAR(255),
    LienWiki varchar(500),
    contours TEXT(1550000),
    lat int,
    lon int
);
