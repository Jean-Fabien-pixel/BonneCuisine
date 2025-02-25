<?php session_start();
require "librairies/fonctions.lib.php";
$bd;
connecterBD($bd);
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
            <div class="col-3 text-end">Courriel :</div>
            <input class="col-4" type="email" name="courriel" id="courriel" required>
        </div>
        <div class="row position-relative d-flex align-items-center justify-content-center mt-2">
            <div class="col-3 text-end">Mot de passe :</div>
            <input class="col-4" type="password" name="mdp" required>
        </div>
        <?php if (!$valide) {
            print ('<div class="text-center text-danger mt-3"> >> Le courriel ou le mot de passe est invalide << </div>');
        } ?>
        <div id="placeErreur" class="text-center text-warning mt-2">&emsp;</div>
        <div class="row mt-4 position-relative d-flex align-items-center justify-content-center">
            <button class="col-md-2 m-2 btn btn-outline-success" type="submit" name="connecter">Se connecter</button>
            <button class="col-md-2 m-2 btn btn-outline-danger" type="reset" name="annuler">Annuler</button>
        </div>
    </form>
    <div class="row text-center mt-2">
        <a href="#" onclick="RecupererEmailMdpOublie(); return false">Mot de passe oublié
        </a>
    </div>
    <?php
} else {
    $courriel = $_GET["identifiantsOublies"];
    if (VerifierEmail($bd, $courriel)) {
        EnvoyerMessageChangeMdp($bd, $courriel);
        print("<div class='text-center text-success mt-4'> <strong>Email envoyé !</strong><br><br> Un courriel vous a été envoyé pour réinitialiser votre mot de passe.</div>");
    } else {
        print("<div class='text-center text-danger mt-4'><strong>Ouups !!</strong> <br><br>Vous n'êtes pas un utilisateur de notre plateforme !</div>");
    }
}
require "inclus/piedPage.inc";
?>