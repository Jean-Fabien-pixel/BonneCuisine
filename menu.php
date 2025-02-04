<?php
require "inclus/entete.inc";
require "librairies/fonctions.lib.php";
$bd = null;
connecterBD($bd);?>

<h3 class="col">Voici nos différents menus :</h3><br><br>
<?php
AfficherProduit($bd);
print("<p class='bg-warning'>P.S. Le montant en devise <strong>USD</strong> est à titre indicatif. Ce dernier sera calculé au taux du jour lorsque la commande sera validée et effectuée</p>");
require "inclus/piedPage.inc";
?>