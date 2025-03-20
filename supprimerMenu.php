<?php session_start();
require "librairies/fonctions.lib.php";
require "classes/menuClass.php";

$translations = ChoisirLangue();

require('inclus/enteteConnecte.inc');

$bd = null;
connecterBD($bd);
$lang = $_COOKIE['lang'] ?? 'fr';
if (isset($_GET["action"]) && $_GET["action"] == "supprimer") {
    if (!empty($_POST['chk']) && is_array($_POST['chk'])) {
        foreach ($_POST['chk'] as $menu) {
            $menu_delete = new MenuClass($menu);
            $menu_delete->supprimerMenuBD($bd);
        }
    }
}

?>
    <h3><?= $translations["supprimerMenu_h3"] ?></h3>
    <form action="supprimerMenu.php?action=supprimer" method="post" class="mt-3">

        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>&nbsp</th>
                    <th><?= $translations["ajouterMenu_nom"] ?></th>
                    <th><?= $translations["ajouterMenu_description"] ?></th>
                    <th><?= $translations["ajouterMenu_prix"] ?></th>
                </tr>
                <?php AfficherEnregistrement($bd); ?>
            </table>
        </div>
        <br>
        <div class="text-center">
            <input type="submit" value="<?= $translations["btnSupprimer"] ?>"
                   onclick="return ValiderSuppression();">
            <input type="reset" value="<?= $translations["btnAnnuler"] ?>">
        </div>
    </form>

<?php
require('inclus/piedPageConnecte.inc');
?>