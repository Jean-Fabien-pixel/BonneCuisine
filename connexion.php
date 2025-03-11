<?php session_start();
require "librairies/fonctions.lib.php";
$bd;
connecterBD($bd);
$translations = ChoisirLangue();

$valide = true;
if (isset($_GET["action"])) {
    if ($_GET["action"] == "connecter") {
        $valide = ValiderConnexion($bd, $_POST["courriel"], $_POST["mdp"]);
        if ($valide) {
            $_SESSION["acces"] = "Oui";
            $_SESSION["courriel"] = $_POST["courriel"];
            header("Location: index.php");
        }
    }
}
require "inclus/entete.inc";

if (!(isset($_GET['identifiantsOublies']))) {
    ?>
    <form class="mt-3" method="post" action="connexion.php?action=connecter">
        <div class="row position-relative d-flex align-items-center justify-content-center">
            <div class="col-3 text-end"><?= $translations["connexion_courriel"] ?></div>
            <input class="col-4" type="email" name="courriel" id="courriel" required>
        </div>
        <div class="row position-relative d-flex align-items-center justify-content-center mt-2">
            <div class="col-3 text-end"><?= $translations["connexion_mdp"] ?></div>
            <input class="col-4" type="password" name="mdp" required>
        </div>
        <?php if (!$valide) {
            print '<div class="text-center text-danger mt-3"> >>' . $translations["connexion_erreurConnexion"] . '<< </div>';
        } ?>
        <div id="placeErreur" class="text-center text-warning mt-2">&emsp;</div>
        <div class="row mt-4 position-relative d-flex align-items-center justify-content-center">
            <button class="col-md-2 m-2 btn btn-outline-success" type="submit"
                    name="connecter"><?= $translations["connexion_btnConnecter"] ?></button>
            <button class="col-md-2 m-2 btn btn-outline-danger" type="reset"
                    name="annuler"><?= $translations["connexion_btnReset"] ?></button>
        </div>
    </form>
    <div class="row text-center mt-2">
        <a href="#" onclick="RecupererEmailMdpOublie(); return false"><?= $translations["connexion_mdpOublie"] ?>
        </a>
    </div>
    <?php
} else {
    $courriel = $_GET["identifiantsOublies"];
    if (VerifierEmail($bd, $courriel)) {
        EnvoyerMessageChangeMdp($bd, $courriel, $translations);
        print("<div class='text-center text-success mt-4'>" . $translations["connexion_succes"] . "</div>");
    } else {
        print("<div class='text-center text-danger mt-4'>" . $translations["connexion_echec"] . "</div>");
    }
}
require "inclus/piedPage.inc";
?>