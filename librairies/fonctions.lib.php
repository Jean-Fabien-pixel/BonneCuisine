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

function AfficherMenu($bd)
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
        print ("<a href='commande.php?action=ajouter&no=$ligne->idMenu' class='text-decoration-none'>Ajouter à la commande…</a>");
        print ("</div>");
        print ("</div>");
    }
}

function AfficherPanier($bd, $panier, $cookieName, &$prixTotal)
{
    $prixTotal = 0;
    $select = $bd->prepare("SELECT * FROM panier 
                            JOIN cuisine.menu_fr mf ON mf.idMenu = panier.noProduit 
                            WHERE idPanier = :cookieName");
    $select->execute([
        'cookieName' => $cookieName
    ]);
    $resultat = $select->fetchAll(PDO::FETCH_OBJ);

    foreach ($resultat as $ligne) {
        $id = $ligne->idMenu;
        $nom = htmlspecialchars($ligne->nom);
        $prix = $ligne->prix;
        $quantite = $panier[$id] ?? 1;
        $prix += ($quantite < 10) ? 1 : 0;
        $prixTotal += $prix * $quantite;

        print "<p><strong>Menu: </strong> $nom</p>
               <label for='nbPersonne$id'>Nombre de personnes : 
                   <input type='number' name='nbPersonne[$id]' value='$quantite'>
               </label><br>
               <a href='commande.php?action=supprimer&no=$id' class='text-decoration-none'>Supprimer ce menu</a></p>";
    }
    $taxes = $prixTotal * 0.05 + $prixTotal * 0.09975;
    $prixTotal += $taxes;
    if (isset($_POST['chkLivraison'])) {
        $prixTotal += 15;
    }
    $prixTotal = number_format($prixTotal, 2);
}

function AjouterPanier($bd, &$panier, $cookieName)
{
    if (isset($_GET['no'])) {
        $idMenu = $_GET['no'];
        if (isset($panier[$idMenu])) {
            $panier[$idMenu] += 10;
            $update = $bd->prepare("UPDATE panier set quantite = :quantite where idPanier = :idPanier and noProduit = :idMenu");
            $update->execute([
                'idPanier' => $cookieName,
                'quantite' => $panier[$idMenu],
                'idMenu' => $idMenu
            ]);
        } else {
            $panier[$idMenu] = 10;
            $insert = $bd->prepare("INSERT INTO panier (idPanier, noProduit, quantite, datePanier) VALUES (:idPanier, :noProduit, :quantite, :datePanier)");
            $insert->execute([
                'idPanier' => $cookieName,
                'noProduit' => $idMenu,
                'quantite' => $panier[$idMenu],
                'datePanier' => date('Y-m-d')
            ]);
        }
        setcookie($cookieName, json_encode($panier), time() + 3 * 3600);
    }
}

function SupprimerPanier($bd, &$panier, $cookieName)
{
    if (isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['no'])) {
        $id = $_GET['no'];
        unset($panier[$id]);
        $delete = $bd->prepare("DELETE FROM panier where idPanier = :idPanier and noProduit = :idMenu");
        $delete->execute([
            'idPanier' => $cookieName,
            'idMenu' => $id
        ]);

        if (empty($panier)) {
            setcookie($cookieName, "", time() - 3600); // Supprime le cookie si vide
        } else {
            setcookie($cookieName, json_encode($panier), time() + 3 * 3600);
        }
    }

}

function ModifierPanier($bd, &$panier, $cookieName)
{
    if (isset($_GET['action']) && $_GET['action'] == 'modifier' && isset($_GET['nb'])) {
        foreach ($_POST['nbPersonne'] as $id => $quantite) {
            // Vérifie si la quantité est supérieure à 0
            if ($quantite > 0) {
                // Met à jour la quantité de l'élément dans le panier
                $panier[$id] = $quantite;
                $update = $bd->prepare("UPDATE panier set quantite = :quantite where idPanier = :idPanier and noProduit = :idMenu");
                $update->execute([
                    'idPanier' => $cookieName,
                    'quantite' => $panier[$id],
                    'idMenu' => $id
                ]);
            } else {
                // Si la quantité est 0, on enlève l'élément du panier
                unset($panier[$id]);
                $delete = $bd->prepare("DELETE FROM panier where idPanier = :idPanier and noProduit = :idMenu");
                $delete->execute([
                    'idPanier' => $cookieName,
                    'idMenu' => $id
                ]);
            }

            // Mise à jour du cookie
            if (empty($panier)) {
                setcookie($cookieName, "", time() - 3600); // Supprime le cookie si vide
            } else {
                setcookie($cookieName, json_encode($panier), time() + 3 * 3600);
            }

        }
    }
}

function EnvoyerMessage($bd, $courriel, $panier, $cookieName)
{
    $prixTotal = 0;
    $select = $bd->prepare("SELECT * FROM panier 
                            JOIN cuisine.menu_fr mf ON mf.idMenu = panier.noProduit 
                            WHERE idPanier = :cookieName");
    $select->execute([
        'cookieName' => $cookieName
    ]);
    $resultat = $select->fetchAll(PDO::FETCH_OBJ);

    // Sujet de l'email
    $objet = "Confirmation de votre commande - La Bonne Cuisine";

    // Construire le message
    $message = "Bonjour,\n\n";
    $message .= "Nous vous remercions d'avoir commandé via notre service de traiteur «La Bonne Cuisine». Nos employés s'affairent à cuisiner votre commande pour votre plus grande satisfaction.\n\n";
    $message .= "Voici la description de votre commande :\n";
    foreach ($resultat as $ligne) {
        $id = $ligne->idMenu;
        $nom = htmlspecialchars($ligne->nom);
        $prix = $ligne->prix;
        $quantite = $panier[$id] ?? 1;
        $prixTotal += $prix * $quantite;

        $message .= "-> " . $nom . " pour " . $quantite . " personnes\n\n";
    }
    $taxes = $prixTotal * 0.05 + $prixTotal * 0.09975;
    $prixTotal += $taxes;
    if (isset($_POST['chkLivraison'])) {
        $prixTotal += 15;
    }
    $prixTotal = number_format($prixTotal, 2);
    $message .= "Pour un total de " . $prixTotal . " $\n\n";
    $message .= "Un responsable communiquera avec vous dans les plus brefs délais.\n\n";
    $message .= "À bientôt!\n\n";
    $message .= "L'équipe de La Bonne Cuisine";

    // En-têtes de l'email
    $headers = "From: etudiant.info@collegealma.ca\r\n";
    $headers .= "Reply-To: etudiant.info@collegealma.ca\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Envoyer l'email
    mail($courriel, $objet, $message, $headers);

    setcookie($cookieName, "", time() - 3600); // Supprime le cookie si vide
}