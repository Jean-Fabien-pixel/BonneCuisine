<?php
// Trouver le cookie existant (si présent)
$cookieName = null;
foreach ($_COOKIE as $name => $value) {
    if (str_starts_with($name, "panier_")) { // Si le cookie commence par "panier"
        $cookieName = $name;
        $panier = json_decode($value, true);
        break;
    }
}

if (!$cookieName || !isset($_COOKIE[$cookieName])) {
    $cookieName = "panier_" . uniqid();
    $panier = [];
    setcookie($cookieName, json_encode($panier), time() + 3 * 3600);
} else {
    $panier = json_decode($_COOKIE[$cookieName], true);
}

require "inclus/entete.inc";
require "librairies/fonctions.lib.php";
$bd;
connecterBD($bd);

if (isset($_GET['action']) && $_GET['action'] == 'ajouter' && isset($_GET['no'])) {
    AjouterPanier($bd, $panier, $cookieName);
} elseif (isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['no'])) {
    SupprimerPanier($bd, $panier, $cookieName);
} elseif (!empty($_POST['email']) && isset($_POST['envoyerCommande'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    // Envoyer l'email
    EnvoyerMessageCommande($bd, $email, $panier, $cookieName);
} elseif (isset($_GET['action']) && $_GET['action'] == 'modifier' && isset($_GET['nb'])) {
    ModifierPanier($bd, $panier, $cookieName);
}
// Affichage du panier
if (!empty($_POST['email'])) {
    echo "<p class='text-success text-center fw-bold'>Nous avons bien reçu votre commande. <br>
            Votre colis vous sera livré dans les plus brefs délais. <br>
            Merci de nous faire confiance !</strong></p>";
} elseif (empty($panier)) {
    print "<h3>Votre Commande :</h3>";
    print "&emsp;&emsp;&emsp; Aucune commande !";
} else {
    $nb = count($panier);
    print "<h3>Votre Commande :</h3>";
    print ("<form method='post' action='commande.php?action=modifier&nb=$nb'>");
    AfficherPanier($bd, $panier, $cookieName);
    print ("</form>");

}
if (!empty($_POST['email']) && isset($_POST['envoyerCommande'])) {
    print "";
}
?>

<?php
require "inclus/piedPage.inc";
?>