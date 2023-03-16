CREATE DATABASE IF NOT EXISTS teamease;
ALTER DATABASE teamease CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

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
  FOREIGN KEY (siret) REFERENCES COMPANY(siret) ON DELETE CASCADE
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
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id) ON DELETE CASCADE,
  FOREIGN KEY (id_material) REFERENCES MATERIAL(id) ON DELETE CASCADE
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
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id) ON DELETE CASCADE,
  FOREIGN KEY (id_category) REFERENCES CATEGORY(id) ON DELETE CASCADE
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
  id INT NOT NULL AUTO_INCREMENT,
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

CREATE TABLE SCHEDULE
(
  id INT NOT NULL AUTO_INCREMENT,
  day VARCHAR(255) NOT NULL,
  startHour TIME NOT NULL,
  endHour TIME NOT NULL,
  id_activity INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id) ON DELETE CASCADE
);

CREATE TABLE RESERVATION
(
  id INT NOT NULL,
  attendee INT NOT NULL,
  id_activity INT NOT NULL,
  siret CHAR(14) NOT NULL,
  id_location INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id) ON DELETE CASCADE,
  FOREIGN KEY (siret) REFERENCES COMPANY(siret) ON DELETE CASCADE,
  FOREIGN KEY (id_location) REFERENCES LOCATION(id) ON DELETE CASCADE
);

CREATE TABLE INVOICE
(
  id INT NOT NULL,
  amount INT NOT NULL,
  paymentDay DATE NOT NULL,
  details TEXT NOT NULL,
  id_reservation INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id) ON DELETE CASCADE
);

CREATE TABLE ROOM
(
  id INT NOT NULL,
  number INT NOT NULL,
  id_location INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_location) REFERENCES LOCATION(id) ON DELETE CASCADE
);

CREATE TABLE COMMENT
(
  id INT NOT NULL,
  content TEXT NOT NULL,
  notation INT NOT NULL,
  id_reservation INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id) ON DELETE CASCADE
);

CREATE TABLE RESERVED
(
  id_attendee INT NOT NULL,
  id_reservation INT NOT NULL,
  FOREIGN KEY (id_attendee) REFERENCES ATTENDEE(id) ON DELETE CASCADE,
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id) ON DELETE CASCADE
);

CREATE TABLE PROVIDER
(
  id INT NOT NULL AUTO_INCREMENT,
  firstName VARCHAR(255) NOT NULL,
  lastName VARCHAR(255) NOT NULL,
  salary INT NOT NULL,
  password VARCHAR(255) NOT NULL,
  rights INT NOT NULL,
  email VARCHAR(255) NOT NULL,
  id_occupation INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_occupation) REFERENCES OCCUPATION(id) ON DELETE CASCADE
);

CREATE TABLE ANIMATE
(
  id_activity INT NOT NULL,
  id_provider INT NOT NULL AUTO_INCREMENT,
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id) ON DELETE CASCADE,
  FOREIGN KEY (id_provider) REFERENCES PROVIDER(id) ON DELETE CASCADE
);

CREATE TABLE MATERIAL_ROOM
(
  id_material INT NOT NULL AUTO_INCREMENT,
  id_room INT NOT NULL,
  FOREIGN KEY (id_material) REFERENCES MATERIAL(id) ON DELETE CASCADE,
  FOREIGN KEY (id_room) REFERENCES ROOM(id) ON DELETE CASCADE
);

CREATE TABLE RESERVATION_SERVICE
(
  id_service INT NOT NULL,
  id_reservation INT NOT NULL,
  FOREIGN KEY (id_service) REFERENCES SERVICE(id) ON DELETE CASCADE,
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id) ON DELETE CASCADE
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
INSERT INTO COMPANY (siret, companyName, email, address, password, rights) VALUES (12345678901235, 'testCompany', 'test@domaine.com','24 rue test', sha2('Respons11', 512), 0);

INSERT INTO OCCUPATION (id, name) VALUES (1, 'Animateur');
INSERT INTO OCCUPATION (id, name) VALUES (2, 'Game Master');
INSERT INTO OCCUPATION (id, name) VALUES (3, 'Coach sportif');

INSERT INTO PROVIDER (firstName, lastName, salary, password, rights, email, id_occupation) VALUES ('Jean', 'Dupont', 2000, sha2('Respons11', 512), 1, 'test@domaine.com', 1);