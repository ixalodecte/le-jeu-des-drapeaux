drop table question;
drop table questionnaire;

CREATE TABLE questionnaire (
    id int Primary Key AUTO_INCREMENT,
    nom varchar(255) NOT NULL,
    description varchar(5000),
    image varchar(255)
);

CREATE TABLE question (
    id int,
    codeIso3 char(3),
    primary key(id,codeIso3),
    FOREIGN KEY (id) REFERENCES questionnaire(id) ON DELETE CASCADE,
    FOREIGN KEY (codeIso3) REFERENCES pays(codeIso3)
);
