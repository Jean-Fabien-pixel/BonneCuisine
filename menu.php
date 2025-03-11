<?php session_start();
require "librairies/fonctions.lib.php";
$translations = ChoisirLangue();

if (isset($_SESSION['courriel'])) {
    require('inclus/enteteConnecte.inc');
} else {
    require('inclus/entete.inc');
}

$bd = null;
connecterBD($bd);
?>
    <h3 class="col"><?= $translations["menu_h3"]; ?></h3><br><br>
<?php
$lang = $_COOKIE['lang'] ?? 'fr';
AfficherMenu($bd, isset($_SESSION['courriel']), $lang, $translations);
print('<p class="bg-warning">' . $translations["menu_texte"] . '</p>');
if (isset($_SESSION['courriel'])) {
    require('inclus/piedPageConnecte.inc');
} else {
    require('inclus/piedPage.inc');
} ?>