<?php session_start();
require "librairies/fonctions.lib.php";
require "classes/menuClass.php";

if (isset($_GET['no'])) {
    $lang = $_COOKIE["lang"] ?? 'fr';
    if (isset($_GET["lang"])) {
        $lang = $_GET["lang"];
        setcookie("lang", $lang, time() + (86400 * 365), "/");
        header("Location: modifierMenu.php?action=modifier&no=" . $_GET['no']);
//        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    $contenu_fichier_json = file_get_contents("json/" . $lang . ".json");
    $translations = json_decode($contenu_fichier_json, true);
} else {
    $translations = ChoisirLangue();
}

require('inclus/enteteConnecte.inc');

$bd = null;
connecterBD($bd);
$lang = $_COOKIE['lang'] ?? 'fr';
if (isset($_GET["id"])) {
    if (!VerifierId($bd, $_GET["id"])) {
        header("Location: modifierMenu.php");
    }
}

if (isset($_GET["action"]) && $_GET["action"] == "modifier") {
    if (isset($_GET["id"]) && !empty($_POST["nom"]) && !empty($_POST["description"]) && !empty($_POST["prix"])) {

        $lang = $_COOKIE["lang"] ?? 'fr';
        $table = "menu_" . $lang;
        $menu = new menuClass($_GET["id"]);

        if ($menu->modifierMenuBD($bd, $_POST["nom"], $_POST["description"], $_POST["prix"], $table)) {
            echo "<p class='text-success'>Modification réussie ✅</p>";
        } else {
            echo "<p class='text-danger'>Erreur lors de la modification ❌</p>";
        }
        var_dump($_POST["nom"], $_POST["description"], $_POST["prix"]);
    }
}

print("<h3>" . $translations["modifierMenu_h3"] . "</h3>");
if (isset($_GET["no"])) {
    $action = $_GET["action"] ?? 'modifier';
    $lang = $_COOKIE["lang"] ?? 'fr';
    $checked = ($lang === 'en') ? "checked" : "";
    $no = $_GET["no"];
    print '
<script>
//    window.history.pushState({}, "", "modifierMenu.php?action=modifier&no=' . $_GET['no'] . '"); 
//    
//    window.onpopstate = function(event) {
//        window.location.href = "modifierMenu.php";
//    };
//window.location.replace("modifierMenu.php");

</script>
<div class="d-flex justify-content-end align-items-center">
    <span class="d-flex align-items-center">
        <div class="me-2">' . $translations["modifierMenu_fr"] . '</div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" ' . $checked . '
            onchange="window.location.href=\'modifierMenu.php?action=' . $action . '&no=' . $no . '&lang=\' + (this.checked ? \'en\' : \'fr\')">
        </div>
        <div class="ms-1">' . $translations["modifierMenu_en"] . '</div>
    </span>
</div>';
    AfficherFormModif($bd, $no, $lang, $translations);
} else {
    AfficherMenuMod($bd, $lang, $translations);
}

?>

<?php
require('inclus/piedPageConnecte.inc');
?>