<?php session_start();
require "librairies/fonctions.lib.php";
$translations = ChoisirLangue();
$bd = null;
connecterBD($bd);

if (isset($_GET["no"]) && isset($_GET["id"])) {
    $delai = $_GET["no"];
    if ($delai >= time()) {
        $hashId = $_GET["id"];
        $usager = VerifierId($bd, $hashId);
        if (!$usager) {
            header("Location:connexion.php");
        }
    } else {
        header("Location: connexion.php");
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'changermdp') {
    if ($_POST["password1"] == $_POST["password2"]) {
        ChangeMdp($bd, $_GET['id'], $_POST["password1"]);
    }
}
if (isset($_SESSION['courriel'])) {
    require('inclus/enteteConnecte.inc');
} else {
    require('inclus/entete.inc');
}

?>
    &emsp;<?= $translations["mdp_salutation"]." ". $usager->nom; ?>,
    <form class="mt-3" method="post" action="motPasse.php?action=changermdp&id=<?= $usager->idUsager ?>">
        <div class="row center">
            <div class="col-3 text-start"><?=$translations["mdp_nouveauMdp1"]?></div>
            <input class="col-4" type="password" id="password1" name="password1" required>
        </div>
        <div class="row center mt-2">
            <div class="col-3 text-start"><?=$translations["mdp_nouveauMdp2"]?></div>
            <input class="col-4" type="password" id="password2" name="password2" required>
        </div>
        <?= "<div class='text-danger text-center mt-3' id='msgErreur'></div>" ?>
        <div class="row mt-4">
            <button class="col-md-2 m-2 btn btn-primary" type="submit" onclick="return VerifierMdp()" name="valider">
                <?=$translations["mdp_btnValider"]?>
            </button>
        </div>
    </form>
<?php
require "inclus/piedPage.inc";
?>