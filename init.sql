CREATE DATABASE IF NOT EXISTS teamease;

Jayllyz
#4620

Jayllyz — 31/01/2023 15:17
CREATE TABLE ACTIVITE
(
  id INT NOT NULL,
  maxParticipant INT NOT NULL,
  PRIMARY KEY (id)
);
Expand
db.sql
3 KB
Zernor — 31/01/2023 16:03
JFreeChart
Jayllyz — 18/02/2023 15:02
je comprends pas pk ya id reservation etc dans cette table
Image
On peut avoir qu'une seule reservation par entreprise?
Minatoco — 18/02/2023 15:03
heu non tu peux en avoir plusieurs
apres j'ai pas regardé ce que vous avez fait dedans
Jayllyz — 18/02/2023 15:05
je peux pas faire l'inscription car j'ai rien à mettre dans les 4 id là
Minatoco — 18/02/2023 15:06
ha heu ouais vous avez du mal les positionné
Zernor — 18/02/2023 15:06
ouai je viens de revoir le cours de modelisation de l'annee derniere
quand y'a 0n ou 1n avec une table 1,1 ou 0,1 la cle etrangere va dans 1,1 ou 0,1
et pas dans les n
j'ai remis a jour la db
Jayllyz — 18/02/2023 15:11
merci
Zernor — 18/02/2023 15:11
je m'etais dit y'avait une douille hier quand je faisais le catalogue
genre dans activity a aucun moment j'avais dans la table une id pour l'entreprise qui organise l'activite
Jayllyz — 18/02/2023 15:14
ya encore une petite modif (sorry) faut mettre le siret en string avec 14 en length
Zernor — 18/02/2023 15:15
siret CHAR(14) NOT NULL,
CREATE TABLE COMPANY
(
  siret CHAR(14) NOT NULL,
  companyName VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
Expand
message.txt
3 KB
change juste la 1ere ligne de company
Jayllyz — 18/02/2023 15:16
ca  change aussi des clés donc je cp tout
Zernor — 18/02/2023 15:17
normalement les cles y'a pas besoin de leur dire le type
Jayllyz — 18/02/2023 15:17
sisi
Zernor — 18/02/2023 15:17
dans le init.sql?
Jayllyz — 18/02/2023 15:17
exemple ici
Image
Zernor — 18/02/2023 15:18
ah ouai
Jayllyz — 18/02/2023 15:18
c good l'inscription je vais rajouter des msg d'erreur moins moche et faire la co
Image
Jayllyz — 18/02/2023 15:57
qlq voit une erreur ?
Image
ok jsuis trop con c bon
Minatoco — 18/02/2023 15:59
d'accord
Zernor — Today at 15:52
CREATE TABLE COMPANY
(
  siret CHAR(14) NOT NULL,
  companyName VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
Expand
message.txt
4 KB
﻿
CREATE TABLE COMPANY
(
  siret CHAR(14) NOT NULL,
  companyName VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  rights INT NOT NULL,
  PRIMARY KEY (siret)
);

CREATE TABLE ESTIMATE
(
  id INT NOT NULL,
  amount INT NOT NULL,
  creationDate DATE NOT NULL,
  siret CHAR(14) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (siret) REFERENCES COMPANY(siret)
);

CREATE TABLE MATERIAL
(
  id INT NOT NULL,
  type VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE CATEGORY
(
  id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE LOCATION
(
  id INT NOT NULL,
  address VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE ATTENDEE
(
  id INT NOT NULL,
  firstName VARCHAR(255) NOT NULL,
  lastName VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE PROVIDER
(
  id INT NOT NULL,
  firstName VARCHAR(255) NOT NULL,
  lastName VARCHAR(255) NOT NULL,
  occupation VARCHAR(255) NOT NULL,
  salary INT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE ROOM
(
  id INT NOT NULL,
  number INT NOT NULL,
  id_location INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_location) REFERENCES LOCATION(id)
);

CREATE TABLE MATERIAL_ROOM
(
  id_material INT NOT NULL,
  id_room INT NOT NULL,
  FOREIGN KEY (id_material) REFERENCES MATERIAL(id),
  FOREIGN KEY (id_room) REFERENCES ROOM(id)
);

CREATE TABLE ACTIVITY
(
  id INT NOT NULL,
  maxAttendee INT NOT NULL,
  duration INT NOT NULL,
  priceAttendee INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  id_room INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_room) REFERENCES ROOM(id)
);

CREATE TABLE RESERVATION
(
  id INT NOT NULL,
  attendee INT NOT NULL,
  id_activity INT NOT NULL,
  siret CHAR(14) NOT NULL,
  id_location INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id),
  FOREIGN KEY (siret) REFERENCES COMPANY(siret),
  FOREIGN KEY (id_location) REFERENCES LOCATION(id)
);

CREATE TABLE INVOICE
(
  id INT NOT NULL,
  amount INT NOT NULL,
  paymentDay DATE NOT NULL,
  details TEXT NOT NULL,
  id_reservation INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id)
);

CREATE TABLE MATERIAL_ACTIVITY
(
  quantity INT NOT NULL,
  id_activity INT NOT NULL,
  id_material INT NOT NULL,
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id),
  FOREIGN KEY (id_material) REFERENCES MATERIAL(id)
);

CREATE TABLE BELONG
(
  id_activity INT NOT NULL,
  id_category INT NOT NULL,
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id),
  FOREIGN KEY (id_category) REFERENCES CATEGORY(id)
);

CREATE TABLE COMMENT
(
  id INT NOT NULL,
  content TEXT NOT NULL,
  notation INT NOT NULL,
  id_reservation INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id)
);

CREATE TABLE RESERVED
(
  id_attendee INT NOT NULL,
  id_reservation INT NOT NULL,
  FOREIGN KEY (id_attendee) REFERENCES ATTENDEE(id),
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id)
);

CREATE TABLE ANIMATE
(
  id_activity INT NOT NULL,
  id_provider INT NOT NULL,
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id),
  FOREIGN KEY (id_provider) REFERENCES PROVIDER(id)
);