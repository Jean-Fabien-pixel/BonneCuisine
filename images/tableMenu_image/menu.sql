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
