<?php session_start();
if (isset($_GET["action"])) {
    if ($_GET["action"] == "deconnecter") {
        session_unset();
        session_destroy();
    }
}
require "librairies/fonctions.lib.php";
$translations = choisirLangue();

// Définition de l'en-tête à inclure
$entete = isset($_SESSION['courriel']) ? 'inclus/enteteConnecte.inc' : 'inclus/entete.inc';

// Inclusion de l'en-tête
require($entete);

?>
    <p>
        <?= $translations["index_texte1"]; ?>
        <a href="mailto:info@labonnecuisine.com">info@labonnecuisine.com</a>
    </p>
    <p class="text-left"><?= $translations["index_texte2"]; ?></p>

<?php if (isset($_SESSION['courriel'])) {
    require('inclus/piedPageConnecte.inc');
} else {
    require('inclus/piedPage.inc');
} ?>