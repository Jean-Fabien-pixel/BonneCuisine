<?php session_start();
if (isset($_GET["action"])) {
    if ($_GET["action"] == "deconnecter") {
        session_unset();
        session_destroy();
    }
}
// Définition de l'en-tête à inclure
$entete = isset($_SESSION['courriel']) ? 'inclus/enteteConnecte.inc' : 'inclus/entete.inc';

// Inclusion de l'en-tête
require($entete);

?>
    <p>
        Bonjour,<br/><br/>
        Pour une rencontre entre amis, une réunion, un party, ou pour toutes
        autres occasions, faites affaire avec notre service de traiteur «La
        Bonne Cuisine». À votre service depuis plus de 15 ans, notre personnel
        saura répondre à vos besoins. <br/><br/>

        Pour tout commentaires, questions ou suggestions, écrivez-nous à
        l'adresse suivante
        <a href="mailto:info@labonnecuisine.com">info@labonnecuisine.com</a>
    </p>
    <p class="text-left">Bon appétit !</p>

<?php if (isset($_SESSION['courriel'])) {
    require('inclus/piedPageConnecte.inc');
} else {
    require('inclus/piedPage.inc');
} ?>