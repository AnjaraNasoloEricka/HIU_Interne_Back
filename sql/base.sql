create database db3;
use db3;

create table td3_User(
    id integer auto_increment primary key,
    username varchar(20),
    pwd varchar(50)
);

create table td3_Produit(
    id integer auto_increment primary key,
    libelle varchar(50),
    prix integer
);

create table td3_Caisse(
    id integer auto_increment primary key,
    num_caisse integer
);

create table td3_Achat(
    id integer auto_increment primary key,
    date_achat datetime
);

create table td3_rel_Achat_Produit(
    id_produit integer,
    id_achat integer,
    quantite integer,
    foreign key (id_produit) references td3_Produit(id),
    foreign key (id_achat) references td3_Achat(id)
);

create table td3_Historique_Achat(
    id integer auto_increment primary key,
    id_caisse integer,
    id_produit integer,
    quantite integer,
    montant integer,
    date_achat datetime,
    foreign key (id_caisse) references td3_Caisse(id),
    foreign key (id_produit) references td3_Produit(id)
);

create table td3_Fluctuation_Qte_Produit(
    id integer auto_increment primary key,
    id_produit integer,
    fluctuation integer,
    foreign key (id_produit) references td3_Produit(id)

);

insert into td3_Caisse(num_caisse) 
values (1),(2),(5);
