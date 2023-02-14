CREATE DATABASE IF NOT EXISTS teamease;

CREATE TABLE ACTIVITE
(
  id INT NOT NULL,
  maxParticipant INT NOT NULL,
  date DATE NOT NULL,
  duree INT NOT NULL,
  prixPersonne INT NOT NULL,
  nom VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE RESERVATION
(
  id INT NOT NULL,
  nom VARCHAR(50) NOT NULL,
  prenom VARCHAR(50) NOT NULL,
  mdp VARCHAR(255) NOT NULL,
  participants INT NOT NULL,
  id_activite INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_activite) REFERENCES ACTIVITE(id)
);

CREATE TABLE DEVIS
(
  id INT NOT NULL,
  montant INT NOT NULL,
  dateCreation DATE NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE FACTURE
(
  id INT NOT NULL,
  montant INT NOT NULL,
  datePaiement DATE NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE SALLE
(
  id INT NOT NULL,
  numero INT NOT NULL,
  addresse DATE NOT NULL,
  id_activite INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_activite) REFERENCES ACTIVITE(id)
);

CREATE TABLE MATERIEL
(
  id INT NOT NULL,
  type VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE MATERIEL_ACTIVITE
(
  quantite INT NOT NULL,
  id_activite INT NOT NULL,
  id_materiel INT NOT NULL,
  FOREIGN KEY (id_activite) REFERENCES ACTIVITE(id),
  FOREIGN KEY (id_materiel) REFERENCES MATERIEL(id)
);

CREATE TABLE CATALOGUE
(
  id INT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE PRESTATION
(
  id INT NOT NULL,
  date DATE NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE ENTREPRISE
(
  siret INT NOT NULL,
  nomEntreprise VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  addresse VARCHAR(255) NOT NULL,
  id_devis INT NOT NULL,
  id_facture INT NOT NULL,
  id_reservation INT NOT NULL,
  id_prestation INT NOT NULL,
  PRIMARY KEY (siret),
  FOREIGN KEY (id_devis) REFERENCES DEVIS(id),
  FOREIGN KEY (id_facture) REFERENCES FACTURE(id),
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id),
  FOREIGN KEY (id_prestation) REFERENCES PRESTATION(id)
);

CREATE TABLE MATERIEL_SALLE
(
  quantite INT NOT NULL,
  id_salle INT NOT NULL,
  id_materiel INT NOT NULL,
  FOREIGN KEY (id_salle) REFERENCES SALLE(id),
  FOREIGN KEY (id_materiel) REFERENCES MATERIEL(id)
);

CREATE TABLE CATEGORIE
(
  id INT NOT NULL,
  nom VARCHAR(255) NOT NULL,
  id_catalogue INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_catalogue) REFERENCES CATALOGUE(id)
);

CREATE TABLE APPARTIENT
(
  id_activite INT NOT NULL,
  id_categorie INT NOT NULL,
  FOREIGN KEY (id_activite) REFERENCES ACTIVITE(id),
  FOREIGN KEY (id_categorie) REFERENCES CATEGORIE(id)
);

CREATE TABLE PRESENT
(
  id_prestation INT NOT NULL,
  id_catalogue INT NOT NULL,
  FOREIGN KEY (id_prestation) REFERENCES PRESTATION(id),
  FOREIGN KEY (id_catalogue) REFERENCES CATALOGUE(id)
);