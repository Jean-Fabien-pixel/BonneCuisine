<?php
require "inclus/entete.inc";
require "librairies/fonctions.lib.php";
$bd = null;
connecterBD($bd);
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

if (isset($_GET['action']) && $_GET['action'] == 'ajouter' && isset($_GET['no'])) {
    AjouterPanier($bd, $panier, $cookieName);
} elseif (isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['no'])) {
    SupprimerPanier($bd, $panier, $cookieName);
} elseif (isset($_GET['action']) && $_GET['action'] == 'modifier' && isset($_GET['nb'])) {
    ModifierPanier($bd, $panier, $cookieName);
}
// Affichage du panier
echo "<h3>Votre Commande :</h3>";

if (empty($panier)) {
    print "&emsp;&emsp;&emsp; Aucune commande !";
} else {
    $nb = count($panier);
    $prixTotal = 0;
    $livraison = isset($_POST['chkLivraison']) ? 15 : 0;


    print ("<form method='post' action='commande.php?action=modifier&nb=$nb'>");
    AfficherPanier($bd, $panier, $cookieName, $prixTotal);
    print "<label><input type='checkbox' name='chkLivraison' value='15' " . ($livraison ? "checked" : "") . "> Livraison (15$)</label><br>
                <button type='submit' name='modifierPanier' class='btn btn-warning mt-3'>Mettre à jour</button>
                <p class='mt-3'>Le montant de votre facture (taxes incluses) : <strong> $prixTotal $</strong></p>
                <button type='submit' class='btn btn-secondary mt-1' name='envoyerCommande' onclick='EnvoyerCommande()'>Envoyer la commande</button>
                <input type='hidden' id='emailInput' name='email'>
                <p class='mt-3'>Attention, 1$/personne sera ajouté à la facture pour les groupes de moins de 10 personnes.</p>
            </form>";

    if (!empty($_POST['email']) && isset($_POST['envoyerCommande'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        // Envoyer l'email
        EnvoyerMessage($bd, $email, $panier, $cookieName);
        echo "<p class='text-success'>✅ Email envoyé à : <strong>$email</strong></p>";
    }
}
?>

<?php
require "inclus/piedPage.inc";
?>