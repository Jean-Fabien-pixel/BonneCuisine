<?php session_start();
require "librairies/fonctions.lib.php";
$translations = ChoisirLangue();

require('inclus/enteteConnecte.inc');

$bd = null;
connecterBD($bd);
$lang = $_COOKIE['lang'] ?? 'fr';
if (isset($_GET["id"])) {
    if (!VerifierId($bd, $_GET["id"])) {
        header("Location: modifierMenu.php");
    }
}

if (isset($_GET["action"])) {
    if ($_GET["action"] == "modifier") {
        if (isset($_GET["id"])) {
            ModifierMenu($bd, $lang, $_GET["id"], $_POST["nom"], $_POST["description"], $_POST["prix"]);
            header("Location: modifierMenu.php");
        }
    }
}

print("<h3>" . $translations["modifierMenu_h3"] . "</h3>");
if (isset($_GET["no"])) {
    $checked = isset($_POST['switch_mode']) ? "checked" : ""; // Vérifie si le switch a été activé
    print '
<div class="d-flex justify-content-end align-items-center">
    <span class="d-flex align-items-center">
        <div class="me-2">' . $translations["modifierMenu_fr"] . '</div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault"
            onchange="window.location.href=\'?lang=\' + (this.checked ? \'en\' : \'fr\')">
        </div>
        <div class="ms-1">' . $translations["modifierMenu_en"] . '</div>
    </span>
</div>';
    AfficherFormModif($bd, $_GET["no"], $lang, $translations);
} else {
    AfficherMenuMod($bd, $lang, $translations);
}

?>

<?php
require('inclus/piedPageConnecte.inc');
?>