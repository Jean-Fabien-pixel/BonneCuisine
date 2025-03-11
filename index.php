<?php session_start();
if (isset($_GET["action"])) {
    if ($_GET["action"] == "deconnecter") {
        session_unset();
        session_destroy();
    }
}
require "librairies/fonctions.lib.php";
$translations = ChoisirLangue();

// Définition de l'en-tête à inclure
$entete = isset($_SESSION['courriel']) ? 'inclus/enteteConnecte.inc' : 'inclus/entete.inc';
$piedPage = isset($_SESSION['courriel']) ? 'inclus/piedPageConnecte.inc' : 'inclus/piedPage.inc';
// Inclusion de l'en-tête
require($entete);

?>
    <p>
        <?= $translations["index_texte1"]; ?>
    </p>
    <p class="text-left"><?= $translations["index_texte2"]; ?></p>

<?php require($piedPage)?>