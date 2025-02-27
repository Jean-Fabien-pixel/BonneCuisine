<?php
$bd = mysqli_connect("localhost", "root", "infoMAC420", "cuisine");

if (mysqli_connect_errno()) {
    die("Échec de connexion à MySQL : " . mysqli_connect_error());
}

$bd->set_charset("utf8");

// Supposons que les paniers sont stockés avec une date et qu'on veut supprimer ceux plus vieux que 24h
$requete = "DELETE FROM panier WHERE datePanier < NOW() - INTERVAL 1 DAY";

if (mysqli_query($bd, $requete)) {
    echo "✅ Suppression des paniers désuets effectuée !\n";
} else {
    echo "❌ Erreur : " . mysqli_error($bd) . "\n";
}

mysqli_close($bd);
?>
