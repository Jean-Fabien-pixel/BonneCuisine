<?php

function connecterBD(&$bd)
{
    try {
        $bd = new PDO('mysql:host=localhost; dbname=cuisine; charset=utf8', 'root', 'infoMAC420');
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        echo "Echec : " . $e->getMessage();
    }
}

function AfficherMenu($bd, $isSessionActive, $lang, $translations)
{
    // Déterminer la table de menu selon la langue
    $tableMenu = "menu_" . $lang;

    $requete = $bd->prepare("SELECT * FROM $tableMenu");
    $requete->execute();
    $resultat = $requete->fetchAll(PDO::FETCH_OBJ);
    foreach ($resultat as $ligne) {
        print ("<div class = 'row mb-4 position-relative'>");
        print ("<div class ='col-md-2 col position-relative d-flex align-items-center justify-content-center' >");
        print ("<img class = 'img-fluid' src ='images/tableMenu_image/$ligne->idMenu.png'>");
        print ("</div>");
        print ("<div class ='col-md-8 col position-relative' style = 'text-align: start;'>");
        print ("<div class ='position-relative top-50 start-50 translate-middle'>");
        print ("<strong>" . $translations["menu_nom"] . " </strong>$ligne->nom <br/>");
        print ("<strong>" . $translations["menu_remarque"] . " </strong>$ligne->description <br/>");
        print ("<strong>" . $translations["menu_prix"] . " </strong>$ligne->prix \$ CAD <br/>");
        print ("</div>");
        print ("</div>");
        print ("<div class='col-md-1 d-flex justify-content-center align-items-center'>");
        if (!$isSessionActive) {
            print ("<a href='commande.php?action=ajouter&no=$ligne->idMenu' class='text-decoration-none'>"
                . $translations["menu_ajouterCommande"] . "</a>");
        }
        print ("</div>");
        print ("</div>");
    }
}

function AfficherPanier($bd, $panier, $cookieName, $translations)
{
    // Déterminer la table de menu selon la langue
    $lang = $_COOKIE["lang"] ?? 'fr';
    $tableMenu = "menu_" . $lang;

    $prixTotal = 0;
    $select = $bd->prepare("SELECT * FROM panier 
                            JOIN cuisine.$tableMenu menu ON menu.idMenu = panier.noProduit 
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

        print "<p><strong>" . $translations["commande_menu"] . " </strong> $nom</p>
               <label for='nbPersonne$id'>" . $translations["commande_nbPersonne"] . " 
                   <input type='number' name='nbPersonne[$id]' id='nbPersonne$id' value='$quantite' min='0'>
               </label><br>
               <a href='commande.php?action=supprimer&no=$id' class='text-decoration-none'>" . $translations["commande_supprimerMenu"] . "</a></p>";
    }
    $taxes = $prixTotal * 0.05 + $prixTotal * 0.09975;
    $prixTotal += $taxes;
    if (isset($_POST['chkLivraison'])) {
        $prixTotal += 15 + 15 * 0.05 + 15 * 0.09975;
    }
    $prixTotal = number_format($prixTotal, 2);
    $livraison = isset($_POST['chkLivraison']) ? 15 : 0;
    print "<label><input type='checkbox' name='chkLivraison' value='15' " . ($livraison ? "checked" : "") . "> " . $translations["commande_livraison"] . " (15$)</label><br>
                <button type='submit' name='modifierPanier' class='btn btn-outline-success mt-3 btn-sm'>" . $translations["commande_mettreAjour"] . "</button>
                <p class='mt-3'>" . $translations["commande_prixTotal"] . " <strong> $prixTotal $</strong></p>
                <button type='submit' class='btn btn-secondary mt-1' name='envoyerCommande' onclick='EnvoyerCommande()'>"
        . $translations["commande_envoyerCommande"] . "</button>
                <input type='hidden' id='emailInput' name='email' value=''>
                <p class='mt-3'>" . $translations["commande_avis"] . "</p>
            ";
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

function EnvoyerMessageCommande($bd, $courriel, $panier, $cookieName)
{
    // Déterminer la langue et charger la traduction
    $lang = $_COOKIE["lang"] ?? 'fr';
    $translations = json_decode(file_get_contents("json/" . $lang . ".json"), true);

    // Déterminer la table de menu selon la langue
    $tableMenu = "menu_" . $lang;

    $prixTotal = 0;
    $select = $bd->prepare("SELECT * FROM panier 
                            JOIN cuisine.$tableMenu menu ON menu.idMenu = panier.noProduit 
                            WHERE idPanier = :cookieName");
    $select->execute([
        'cookieName' => $cookieName
    ]);
    $resultat = $select->fetchAll(PDO::FETCH_OBJ);

    // Construire le message en utilisant les traductions
    $message = $translations["email_intro"];
    $message .= $translations["email_description"];

    foreach ($resultat as $ligne) {
        $id = $ligne->idMenu;
        $nom = htmlspecialchars($ligne->nom);
        $prix = $ligne->prix;
        $quantite = $panier[$id] ?? 1;
        $prixTotal += $prix * $quantite;

        $message .= str_replace(["{nom}", "{quantite}"], [$nom, $quantite], $translations["email_produit"]);
    }

    $taxes = $prixTotal * 0.05 + $prixTotal * 0.09975;
    $prixTotal += $taxes;
    if (isset($_POST['chkLivraison'])) {
        $prixTotal += 15 + 15 * 0.05 + 15 * 0.09975;
    }
    $prixTotal = number_format($prixTotal, 2);

    $message .= str_replace("{prixTotal}", $prixTotal, $translations["email_total"]);
    $message .= $translations["email_contact"];
    $message .= $translations["email_conclusion"];

    // Sujet de l'email
    $objet = $translations["email_sujet"];

    // En-têtes de l'email
    $headers = "From: etudiant.info@collegealma.ca\r\n";
    $headers .= "Reply-To: etudiant.info@collegealma.ca\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Envoyer l'email
    mail($courriel, $objet, $message, $headers);

    // Supprimer le panier après envoi
    setcookie($cookieName, "", time() - 3600);
    $delete = $bd->prepare("DELETE FROM panier WHERE idPanier = :idPanier");
    $delete->execute([
        'idPanier' => $cookieName,
    ]);
}

function ValiderConnexion($bd, $courriel, $password)
{
    $valide = true;
    $nb = 0;
    $requete = $bd->prepare('SELECT * FROM usager WHERE courriel = :courriel');
    $requete->execute([
        'courriel' => $courriel
    ]);

    $nb = $requete->rowCount();
    if ($nb == 0) {
        $valide = false;
    } else {
        $ligne = $requete->fetch(PDO::FETCH_OBJ);
        if (password_verify($password, $ligne->motPasse)) {
            $valide = true;
        } else {
            $valide = false;
        }
    }
    return $valide;
}

function VerifierEmail($bd, $courriel)
{
    $requete = $bd->prepare('SELECT * FROM usager WHERE courriel = :courriel');
    $requete->execute([
        'courriel' => $courriel
    ]);
    if ($requete->rowCount() > 0) {
        return true;
    }
    return false;
}

function EnvoyerMessageChangeMdp($bd, $courriel, $translations)
{
    // Déterminer la langue et charger la traduction
    $lang = $_COOKIE["lang"] ?? 'fr';

    // Récupérer l'utilisateur
    $requete = $bd->prepare('SELECT * FROM usager WHERE courriel = :courriel');
    $requete->execute([
        'courriel' => $courriel
    ]);
    $resultat = $requete->fetch(PDO::FETCH_OBJ);

    // Génération du token et du lien
    $no = time() + 5 * 60;
    $id = $resultat->idUsager;
    $id = password_hash($id, PASSWORD_DEFAULT);

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $lien = "$protocol://" . $_SERVER['SERVER_NAME'] . "/bonnecuisine/motPasse.php?no=$no&id=" . urlencode($id);

    // Construire le message avec les traductions
    $message = $translations["email_mdp_intro"];
    $message .= str_replace("{lien}", $lien, $translations["email_mdp_lien"]);
    $message .= $translations["email_mdp_expiration"];
    $message .= $translations["email_mdp_ignore"];
    $message .= $translations["email_mdp_conclusion"];

    // Sujet du mail
    $objet = $translations["email_mdp_sujet"];

    // En-têtes du mail
    $headers = "From: etudiant.info@collegealma.ca\r\n";
    $headers .= "Reply-To: etudiant.info@collegealma.ca\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Envoi de l'email
    return mail($courriel, $objet, $message, $headers);
}

function VerifierId($bd, $hashId)
{
    $requete = $bd->prepare('SELECT * FROM usager');
    $requete->execute();
    $resultat = $requete->fetchAll(PDO::FETCH_OBJ);
    foreach ($resultat as $ligne) {
        if (password_verify($ligne->idUsager, $hashId)) {
            return $ligne;
        }
    }
    return false;
}

function ChangeMdp($bd, $usager, $mdp)
{
    $requete = $bd->prepare('UPDATE usager SET motPasse = :motPasse WHERE idUsager = :idUsager');
    $requete->execute([
        'motPasse' => password_hash($mdp, PASSWORD_DEFAULT),
        'idUsager' => $usager
    ]);
    header('Location: connexion.php');
}

function choisirLangue()
{
    $lang = $_COOKIE["lang"] ?? 'fr';
    if (isset($_GET["lang"])) {
        $lang = $_GET["lang"];
        setcookie("lang", $lang, time() + (86400 * 365), "/");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    $contenu_fichier_json = file_get_contents("json/" . $lang . ".json");
    return json_decode($contenu_fichier_json, true);
}