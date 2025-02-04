#### Serveur mySQL 
## Hôte: localhost
## Nom usager: root

#### BD pour labonnecuisine.com
## Nom de la base de donnée: cuisine

CREATE DATABASE cuisine
	DEFAULT CHARACTER SET utf8;
USE cuisine;

#########################################################################
#DROP TABLE menu_fr;
CREATE TABLE menu_fr 
(
  	idMenu smallint NOT NULL AUTO_INCREMENT,
  	nom varchar (50) NOT NULL,
  	description varchar (250) NOT NULL,
  	prix float NOT NULL,
  	PRIMARY KEY (idMenu)
);

insert into menu_fr (nom, description, prix) values ('Crudités', 'Nos crudités sont des carottes, du celeri, des choux-fleur, du brocoli, des concombres et des tomates. ', 7.50);
insert into menu_fr (nom, description, prix) values ('Pizza', 'Notre pizza est cuite dans un four à bois ancestrale et elle est toute garnis.', 8.00);
insert into menu_fr (nom, description, prix) values ('Mets chinois', 'Les mets chinois comprennent du riz au poulet, des nouilles chinoises, des egg-rolls et du chow main.', 8.50);
insert into menu_fr (nom, description, prix) values ('Sandwichs et Salades', 'Ce menu comprend une salade du chef, une salade de choux ainsi que des sandwichs aux oeufs, poulet et jambon.', 7.75);
insert into menu_fr (nom, description, prix) values ('Viandes froides et Salades', 'Ce menu comprend une salade du chef, une salade de choux ainsi qu\'une variété de viandes froides (4).', 8.25);

#########################################################################
#DROP TABLE menu_en;
CREATE TABLE menu_en 
(
  idMenu smallint NOT NULL AUTO_INCREMENT,
  nom varchar (50) NOT NULL,
  description varchar (250) NOT NULL,
  prix float NOT NULL,
  PRIMARY KEY (idMenu)
);

insert into menu_en (nom, description, prix) values ('Raw vegetables', 'Our raw vegetables are carrots, celery, cauliflower, broccoli, cucumbers and tomatoes. ', 7.50);
insert into menu_en (nom, description, prix) values ('Pizza', 'Our pizza is cooked in an ancestral wood oven and it is fully garnished.', 8.00);
insert into menu_en (nom, description, prix) values ('Chinese food', 'Chinese food includes chicken rice, chinese noodles, egg rolls and chow main.', 8.50);
insert into menu_en (nom, description, prix) values ('Sandwichs and Salads', 'This menu includes a chef\'s salad, a coleslaw as well as egg, chicken and ham sandwiches.', 7.75);
insert into menu_en (nom, description, prix) values ('Cold meats and Salads', 'This menu includes a chef\'s salad, a coleslaw and a variety of cold meats (4).', 8.25);


#########################################################################
# drop table client;
CREATE TABLE client
(	idClient varchar(8) NOT NULL,
	prenom varchar (25),
	nom varchar (25),
	courriel varchar (50),
	telephone varchar (10),
	PRIMARY KEY (idClient)
);

#########################################################################
# drop table facture;
CREATE TABLE facture
(	idFacture smallint NOT NULL auto_increment,
	noClient varchar(8) NOT NULL,
	dateLivraison datetime,
	montant float NOT NULL,
	commentaire varchar (250),
	PRIMARY KEY (idFacture),
	FOREIGN KEY (noClient) REFERENCES client(idClient)
);

#########################################################################
# drop table commande;
CREATE TABLE commande
(	idCommande smallint NOT NULL auto_increment,
	noFacture smallint NOT NULL,
	noMenu smallint NOT NULL,
	quantite smallint NOT NULL,
	PRIMARY KEY (idCommande, noMenu),
	FOREIGN KEY (noFacture) REFERENCES facture(idFacture)
);

#########################################################################
insert into client (idClient, prenom, nom, courriel, telephone) values ('JB012345', 'Jean','Bouffe', 'etudiant.info@collegealma.ca', '4186682387');
insert into client (idClient, prenom, nom, courriel, telephone) values ('NB000001', 'Nancy','Bluteau', 'nancy.bluteau@collegealma.ca', '4186682387');
insert into client (idClient, prenom, nom, courriel, telephone) values ('VN999999', 'Prenom','Nom', 'prenom.nom@collegealma.ca', '4186682387');

#########################################################################
# drop table usager;
CREATE TABLE usager
(	idUsager smallint NOT NULL auto_increment,
	nom varchar(45) NOT NULL,
	motPasse varchar (250) NOT NULL,
	courriel varchar (50),
	PRIMARY KEY (idUsager)
);

#########################################################################
insert into usager (nom, motPasse, courriel) values ('nancy', '12345','nancy.bluteau@collegealma.ca');
insert into usager (nom, motPasse, courriel) values ('admin', '98765','etudiant.info@collegealma.ca');

#########################################################################
# drop table panier;
CREATE TABLE panier
(	idPanier varchar(30) NOT NULL,
	noProduit smallint NOT NULL,
	quantite smallint NOT NULL,
	datePanier datetime NOT NULL,
	PRIMARY KEY (idPanier, noProduit),
	FOREIGN KEY (noProduit) REFERENCES menu_fr(idMenu),
	FOREIGN KEY (noProduit) REFERENCES menu_en(idMenu)
);

#########################################################################
# drop table devise;
CREATE TABLE devise
(	idDevise smallint NOT NULL auto_increment,
	taux float,
	devise_depart varchar(3),
	devise_arrive varchar(3),
	dateDevise datetime,
	PRIMARY KEY (idDevise)
);