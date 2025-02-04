<?php

function formatterTexte($texte)
{
    $newString = str_replace("'", "&apos;", $texte);
    return $newString;
}

function connecterBD(&$bd)
{
    try {
        $bd = new PDO('mysql:host=localhost; dbname=cuisine; charset=utf8', 'root', 'infoMAC420');
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        echo "Echec : " . $e->getMessage();
    }
}

function AfficherProduit($bd)
{
    $requete = $bd->prepare("select * from menu_fr");
    $requete->execute();
    $resultat = $requete->fetchAll(PDO::FETCH_OBJ);
    foreach ($resultat as $ligne) {
        print ("<div class = 'row mb-4 position-relative'>");
        print ("<div class ='col-md-2 col position-relative d-flex align-items-center justify-content-center' >");
        print ("<img class = 'img-fluid' src ='images/tableMenu_image/$ligne->idMenu.png'>");
        print ("</div>");
        print ("<div class ='col-md-8 col position-relative' style = 'text-align: start;'>");
        print ("<div class ='position-relative top-50 start-50 translate-middle'>");
        print ("<strong>Nom : </strong>$ligne->nom <br/>");
        print ("<strong>Remarque : </strong>$ligne->description <br/>");
        print ("<strong>Prix : </strong>$ligne->prix \$ CAD <br/>");
        print ("</div>");
        print ("</div>");
        print ("<div class='col-md-1 d-flex justify-content-center align-items-center'>");
        print ("<a href='#' class='text-decoration-none'>Ajouter à la commande…</a>");
        print ("</div>");
        print ("</div>");
    }
}