CREATE DATABASE IF NOT EXISTS teamease;
ALTER DATABASE teamease CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE COMPANY
(
  siret CHAR(14) NOT NULL,
  companyName VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  rights INT NOT NULL,
  token VARCHAR(255),
  authToken VARCHAR(60),
  confirm_signup INT NOT NULL DEFAULT 0,
  PRIMARY KEY (siret)
);

CREATE TABLE MATERIAL
(
  id INT NOT NULL AUTO_INCREMENT,
  type VARCHAR(255) NOT NULL,
  quantity INT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE CATEGORY
(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE LOCATION
(
  id INT NOT NULL AUTO_INCREMENT,
  address VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE ATTENDEE
(
  id INT NOT NULL AUTO_INCREMENT,
  firstName VARCHAR(255) NOT NULL,
  lastName VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE OCCUPATION
(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  salary INT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE SERVICE
(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  price INT NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE MATERIAL_LOCATION
(
  quantity INT NOT NULL,
  id_material INT NOT NULL,
  id_location INT NOT NULL,
  FOREIGN KEY (id_material) REFERENCES MATERIAL(id) ON DELETE CASCADE,
  FOREIGN KEY (id_location) REFERENCES LOCATION(id) ON DELETE CASCADE
);

CREATE TABLE ROOM
(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  id_location INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_location) REFERENCES LOCATION(id) ON DELETE CASCADE
);

CREATE TABLE PROVIDER
(
  id INT NOT NULL AUTO_INCREMENT,
  firstName VARCHAR(255) NOT NULL,
  lastName VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  rights INT NOT NULL,
  email VARCHAR(255) NOT NULL,
  id_occupation INT NOT NULL,
  token VARCHAR(255),
  confirm_signup INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  FOREIGN KEY (id_occupation) REFERENCES OCCUPATION(id) ON DELETE CASCADE
);

CREATE TABLE MATERIAL_ROOM
(
  quantity INT NOT NULL,
  id_material INT NOT NULL,
  id_room INT NOT NULL,
  FOREIGN KEY (id_material) REFERENCES MATERIAL(id) ON DELETE CASCADE,
  FOREIGN KEY (id_room) REFERENCES ROOM(id) ON DELETE CASCADE
);

CREATE TABLE AVAILABILITY
(
  id INT NOT NULL AUTO_INCREMENT,
  day VARCHAR(255) NOT NULL,
  id_provider INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_provider) REFERENCES PROVIDER(id) ON DELETE CASCADE
);

CREATE TABLE ACTIVITY
(
  id INT NOT NULL AUTO_INCREMENT,
  maxAttendee INT NOT NULL,
  duration INT NOT NULL,
  priceAttendee INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  id_room INT NOT NULL,
  status INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_room) REFERENCES ROOM(id) ON DELETE CASCADE
);

CREATE TABLE RESERVATION
(
  id INT NOT NULL AUTO_INCREMENT,
  attendee INT NOT NULL,
  id_activity INT NOT NULL,
  siret CHAR(14) NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  status INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id) ON DELETE CASCADE,
  FOREIGN KEY (siret) REFERENCES COMPANY(siret) ON DELETE CASCADE
);

CREATE TABLE ESTIMATE
(
  id INT NOT NULL AUTO_INCREMENT,
  amount INT NOT NULL,
  creationDate DATE NOT NULL,
  id_reservation INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id) ON DELETE CASCADE
);

CREATE TABLE INVOICE
(
  id INT NOT NULL AUTO_INCREMENT,
  amount INT NOT NULL,
  paymentDay DATE NOT NULL,
  details TEXT NOT NULL,
  id_reservation INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id) ON DELETE CASCADE
);

CREATE TABLE MATERIAL_ACTIVITY
(
  quantity INT NOT NULL,
  id_activity INT NOT NULL,
  id_material INT NOT NULL,
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id) ON DELETE CASCADE,
  FOREIGN KEY (id_material) REFERENCES MATERIAL(id) ON DELETE CASCADE
);

CREATE TABLE BELONG
(
  id_activity INT NOT NULL,
  id_category INT NOT NULL,
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id) ON DELETE CASCADE,
  FOREIGN KEY (id_category) REFERENCES CATEGORY(id) ON DELETE CASCADE
);

CREATE TABLE COMMENT
(
  id INT NOT NULL AUTO_INCREMENT,
  content TEXT NOT NULL,
  notation INT NOT NULL,
  date DATE NOT NULL,
  id_reservation INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id) ON DELETE CASCADE
);

CREATE TABLE RESERVED
(
  id_attendee INT NOT NULL AUTO_INCREMENT,
  id_reservation INT NOT NULL,
  FOREIGN KEY (id_attendee) REFERENCES ATTENDEE(id) ON DELETE CASCADE,
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id) ON DELETE CASCADE
);

CREATE TABLE ANIMATE
(
  id_activity INT NOT NULL,
  id_provider INT NOT NULL,
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id) ON DELETE CASCADE,
  FOREIGN KEY (id_provider) REFERENCES PROVIDER(id) ON DELETE CASCADE
);

CREATE TABLE RESERVATION_SERVICE
(
  id_service INT NOT NULL,
  id_reservation INT NOT NULL,
  FOREIGN KEY (id_service) REFERENCES SERVICE(id) ON DELETE CASCADE,
  FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id) ON DELETE CASCADE
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

CREATE TABLE HISTORY
(
  id INT NOT NULL AUTO_INCREMENT,
  date DATE NOT NULL,
  id_provider INT NOT NULL,
  id_activity INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_provider) REFERENCES PROVIDER(id),
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id)
);

CREATE TABLE CART
(
  id INT NOT NULL AUTO_INCREMENT,
  siret CHAR(14) NOT NULL,
  id_activity INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (siret) REFERENCES COMPANY(siret) ON DELETE CASCADE,
  FOREIGN KEY (id_activity) REFERENCES ACTIVITY(id) ON DELETE CASCADE
);

INSERT INTO CATEGORY (id, name) VALUES (1, 'En ligne');
INSERT INTO CATEGORY (id, name) VALUES (2, 'En personne');
INSERT INTO CATEGORY (id, name) VALUES (3, 'Sportive');
INSERT INTO CATEGORY (id, name) VALUES (4, 'Reflexion');
INSERT INTO CATEGORY (id, name) VALUES (5, 'Culturelle');
INSERT INTO CATEGORY (id, name) VALUES (6, 'Musique');
INSERT INTO CATEGORY (id, name) VALUES (7, "Coopératif");
INSERT INTO CATEGORY (id, name) VALUES (8, "Compétitif");

INSERT INTO COMPANY (siret, companyName, email, address, password, rights, token, confirm_signup) VALUES (12345678901234, 'TeamEase', 'teamease@gmail.com', '242 rue faubourg Saint-Antoine', sha2('Respons11', 512), 2, '', 1);
INSERT INTO COMPANY (siret, companyName, email, address, password, rights, token, confirm_signup) VALUES (12345678901235, 'testCompany', 'test@domaine.com','24 rue test', sha2('Respons11', 512), 0, '', 1);

INSERT INTO OCCUPATION (name, salary) VALUES ('Animateur', 200);
INSERT INTO OCCUPATION (name, salary) VALUES ('Game Master', 200);
INSERT INTO OCCUPATION (name, salary) VALUES ('Coach sportif', 300);

INSERT INTO PROVIDER (firstName, lastName, password, rights, email, id_occupation, token, confirm_signup) VALUES ('Jean', 'Dupont', sha2('Respons11', 512), 1, 'jean@gmail.com', 1, '', 1);

INSERT INTO LOCATION (name, address) VALUES ('Paris', '24 rue test');
INSERT INTO LOCATION (name, address) VALUES ('Lyon', '24 rue test');

INSERT INTO ROOM(name, id_location) VALUES ('Salle 1', 1);
INSERT INTO ROOM(name, id_location) VALUES ('Salle 2', 2);
INSERT INTO ROOM(name, id_location) VALUES ('Salle 3', 2);

INSERT INTO ACTIVITY (maxAttendee, duration, priceAttendee, name, description, id_room, status) VALUES (10, 60, 10, 'Escape Game', 'Escape Game', 1, 1);
INSERT INTO ACTIVITY (maxAttendee, duration, priceAttendee, name, description, id_room, status) VALUES (10, 60, 20, 'Yoga Class', 'Join our yoga class and improve your flexibility and mindfulness.', 1, 1);
INSERT INTO ACTIVITY (maxAttendee, duration, priceAttendee, name, description, id_room, status) VALUES (20, 90, 30, 'Cooking Workshop', 'Learn how to make delicious dishes with our experienced chefs.', 2, 1);
INSERT INTO ACTIVITY (maxAttendee, duration, priceAttendee, name, description, id_room, status) VALUES (15, 120, 25, 'Painting Session', 'Unleash your creativity with our guided painting session.', 3, 1);
INSERT INTO ACTIVITY (maxAttendee, duration, priceAttendee, name, description, id_room, status) VALUES (8, 45, 15, 'Fitness Bootcamp', 'Get fit and strong with our intense workout program.', 2, 1);

INSERT INTO SCHEDULE(day, startHour, endHour, id_activity) VALUES ('monday', '10:00:00', '18:00:00', 1);
INSERT INTO SCHEDULE(day, startHour, endHour, id_activity) VALUES ('wednesday', '09:00:00', '22:00:00', 1);
INSERT INTO SCHEDULE(day, startHour, endHour, id_activity) VALUES ('friday', '10:00:00', '18:00:00', 2);
INSERT INTO SCHEDULE(day, startHour, endHour, id_activity) VALUES ('saturday', '10:00:00', '18:00:00', 2);
INSERT INTO SCHEDULE(day, startHour, endHour, id_activity) VALUES ('sunday', '10:00:00', '18:00:00', 3);
INSERT INTO SCHEDULE(day, startHour, endHour, id_activity) VALUES ('monday', '10:00:00', '18:00:00', 3);
INSERT INTO BELONG(id_activity, id_category) VALUES (1, 1);
INSERT INTO BELONG(id_activity, id_category) VALUES (1, 3);