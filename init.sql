CREATE DATABASE IF NOT EXISTS teamease;

CREATE TABLE ACTIVITY
(
  id INT NOT NULL,
  maxAttendee INT NOT NULL,
  date DATE NOT NULL,
  duration INT NOT NULL,
  priceAttendee INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE RESERVATION
(
  id INT NOT NULL,
  attendee INT NOT NULL,
  id_activity INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id)
);

CREATE TABLE ESTIMATE
(
  id INT NOT NULL,
  amount INT NOT NULL,
  creationDate DATE NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE INVOICE
(
  id INT NOT NULL,
  amount INT NOT NULL,
  paymentDay DATE NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE ROOM
(
  id INT NOT NULL,
  number INT NOT NULL,
  address DATE NOT NULL,
  id_activity INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id)
);

CREATE TABLE MATERIAL
(
  id INT NOT NULL,
  type VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE MATERIEL_ACTIVITE
(
  quantite INT NOT NULL,
  id_activity INT NOT NULL,
  id_material INT NOT NULL,
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

CREATE TABLE SERVICE
(
  id INT NOT NULL,
  id_activity INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id)
);

CREATE TABLE COMPANY
(
  siret INT NOT NULL,
  companyName VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  rights INT NOT NULL,
  id_estimate INT NOT NULL,
  id_invoice INT NOT NULL,
  id_reservation INT NOT NULL,
  id_service INT NOT NULL,
  PRIMARY KEY (siret),
  FOREIGN KEY (id_estimate) REFERENCES ESTIMATE(id),
  FOREIGN KEY (id_invoice) REFERENCES INVOICE(id),
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id),
  FOREIGN KEY (id_service) REFERENCES SERVICE(id)
);

CREATE TABLE MATERIAL_ROOM
(
  quantity INT NOT NULL,
  id_room INT NOT NULL,
  id_material INT NOT NULL,
  FOREIGN KEY (id_room) REFERENCES ROOM(id),
  FOREIGN KEY (id_material) REFERENCES MATERIAL(id)
);

INSERT INTO CATEGORY (id, name) VALUES (0, 'En ligne');
INSERT INTO CATEGORY (id, name) VALUES (1, 'En personne');
INSERT INTO CATEGORY (id, name) VALUES (2, 'Sportive');
INSERT INTO CATEGORY (id, name) VALUES (3, 'Reflexion');
INSERT INTO CATEGORY (id, name) VALUES (4, 'Culturelle');
INSERT INTO CATEGORY (id, name) VALUES (5, 'Musique');
INSERT INTO CATEGORY (id, name) VALUES (6, 'Coopératif');
INSERT INTO CATEGORY (id, name) VALUES (7, 'Compétitif');