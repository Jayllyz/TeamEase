CREATE DATABASE IF NOT EXISTS teamease;

CREATE TABLE ACTIVITY
(
  id INT NOT NULL AUTO_INCREMENT,
  maxAttendee INT NOT NULL,
  duration INT NOT NULL,
  priceAttendee INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  status INT NOT NULL,
  PRIMARY KEY (id)
);

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
  id INT NOT NULL AUTO_INCREMENT,
  type VARCHAR(255) NOT NULL,
  quantity INT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE MATERIAL_ACTIVITY
(
  quantity INT NOT NULL,
  id_activity INT NOT NULL,
  id_material INT NOT NULL AUTO_INCREMENT,
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id),
  FOREIGN KEY (id_material) REFERENCES MATERIAL(id)
);

CREATE TABLE CATEGORY
(
  id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE BELONG
(
  id_activity INT NOT NULL,
  id_category INT NOT NULL,
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id),
  FOREIGN KEY (id_category) REFERENCES CATEGORY(id)
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

CREATE TABLE OCCUPATION
(
  id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE SERVICE
(
  id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  price INT NOT NULL,
  PRIMARY KEY (id)
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

CREATE TABLE ROOM
(
  id INT NOT NULL,
  number INT NOT NULL,
  id_location INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_location) REFERENCES LOCATION(id)
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

CREATE TABLE PROVIDER
(
  id INT NOT NULL AUTO_INCREMENT,
  firstName VARCHAR(255) NOT NULL,
  lastName VARCHAR(255) NOT NULL,
  salary INT NOT NULL,
  password VARCHAR(255) NOT NULL,
  rights INT NOT NULL,
  emails VARCHAR(255) NOT NULL,
  id_occupation INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_occupation) REFERENCES OCCUPATION(id)
);

CREATE TABLE ANIMATE
(
  id_activity INT NOT NULL,
  id_provider INT NOT NULL AUTO_INCREMENT,
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id),
  FOREIGN KEY (id_provider) REFERENCES PROVIDER(id)
);

CREATE TABLE MATERIAL_ROOM
(
  id_material INT NOT NULL AUTO_INCREMENT,
  id_room INT NOT NULL,
  FOREIGN KEY (id_material) REFERENCES MATERIAL(id),
  FOREIGN KEY (id_room) REFERENCES ROOM(id)
);

CREATE TABLE RESERVATION_SERVICE
(
  id_service INT NOT NULL,
  id_reservation INT NOT NULL,
  FOREIGN KEY (id_service) REFERENCES SERVICE(id),
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id)
);

INSERT INTO CATEGORY (id, name) VALUES (0, 'En ligne');
INSERT INTO CATEGORY (id, name) VALUES (1, 'En personne');
INSERT INTO CATEGORY (id, name) VALUES (2, 'Sportive');
INSERT INTO CATEGORY (id, name) VALUES (3, 'Reflexion');
INSERT INTO CATEGORY (id, name) VALUES (4, 'Culturelle');
INSERT INTO CATEGORY (id, name) VALUES (5, 'Musique');
INSERT INTO CATEGORY (id, name) VALUES (6, "Coopératif");
INSERT INTO CATEGORY (id, name) VALUES (7, "Compétitif");

INSERT INTO COMPANY (siret, companyName, email, address, password, rights) VALUES (12345678901234, 'TeamEase', 'teamease@gmail.com', '242 rue faubourg Saint-Antoine', sha2('Respons11', 512), 2);
INSERT INTO COMPANY (siret, companyName, email, address, password, rights) VALUES (53145866900037, 'ESGI', 'qjolli1@myges.fr', '242 rue faubourg Saint-Antoine', sha2('Respons11', 512), 0);

INSERT INTO OCCUPATION (id, name) VALUES (1, 'Animateur');
INSERT INTO OCCUPATION (id, name) VALUES (2, 'Game Master');
INSERT INTO OCCUPATION (id, name) VALUES (3, 'Coach sportif');
