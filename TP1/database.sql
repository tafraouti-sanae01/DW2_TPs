CREATE DATABASE tp1_db ;

CREATE TABLE students (
  email VARCHAR(190) ,
  nom VARCHAR(100) ,
  prenom VARCHAR(100) ,
  age INT ,
  telephone VARCHAR(50),
  filiere VARCHAR(20),
  annee TINYINT ,
  modules TEXT ,              
  nb_projets INT ,
  projets TEXT ,
  interets TEXT ,
  formations TEXT ,
  competences TEXT ,
  langues TEXT ,
  photo_path VARCHAR(255) ,
  PRIMARY KEY (email)
)


