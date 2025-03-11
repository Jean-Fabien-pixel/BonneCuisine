<?php session_start();
require "librairies/fonctions.lib.php";
$translations = ChoisirLangue();

require('inclus/enteteConnecte.inc');

$bd = null;
connecterBD($bd);
if (isset($_GET['action']) && $_GET['action'] == 'ajouter') {
    AjouterMenu($bd, $_POST['nom_vf'], $_POST['nom_ve'], $_POST['description_vf'], $_POST['description_ve'], $_POST['prix']);
    header('Location: menu.php');
}

print "<h3 class='m-3'>" . $translations["ajouterMenu_h3"] . "</h3>";
print ('<form method="post" enctype="multipart/form-data" action="ajouterMenu.php?action=ajouter">
    <div class="input-group m-3">
      <span class="input-group-text">' . $translations["ajouterMenu_nom"] . '</span>
      <input type="text" placeholder="' . $translations["ajouterMenu_vf"] . '" name="nom_vf" class="form-control" required>
      <input type="text" placeholder="' . $translations["ajouterMenu_ve"] . '" name="nom_ve" class="form-control" required>
    </div>
    <div class="input-group m-3">
      <span class="input-group-text">' . $translations["ajouterMenu_description"] . '</span>
      <input type="text" placeholder="' . $translations["ajouterMenu_vf"] . '" name="description_vf" class="form-control" required>
      <input type="text" placeholder="' . $translations["ajouterMenu_ve"] . '" name="description_ve" class="form-control" required>
    </div>
    <div class="input-group m-3">
      <span class="input-group-text">' . $translations["ajouterMenu_prix"] . '</span>
      <input type="number" name="prix" min="0" step="0.5" class="form-control" required>
    </div>
    <div class="input-group m-3">
      <input type="file" name="imageMenu" class="form-control" required>
    </div>
    <div class="row mt-4 position-relative d-flex align-items-center justify-content-center">
            <button class="col-md-2 m-2 btn btn-outline-success" type="submit"
                    name="sauvegarder">' . $translations["btnSauvegarder"] . '</button>
            <button class="col-md-2 m-2 btn btn-outline-danger" type="reset"
                    name="annuler">' . $translations["btnAnnuler"] . '</button>
        </div>
</form>');
?>


<?php
require('inclus/piedPageConnecte.inc');
?>
