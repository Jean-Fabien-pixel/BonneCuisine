<?php
$bd = mysqli_connect("localhost", "root", "infoMAC420", "cuisine");
if (mysqli_connect_errno()) {
    die("❌ Échec de connexion à MySQL : " . mysqli_connect_error());
}
$bd->set_charset("utf8");

$nom = "Yoctan";
$email = "202210233@collegealma.ca";
$password = password_hash("admin", PASSWORD_DEFAULT);

// Utilisation d'une requête préparée pour éviter les injections SQL
$requete = "INSERT INTO usager (nom, courriel, motPasse) VALUES (?, ?, ?)";
$insert = $bd->prepare($requete);
$insert->execute([$nom, $email, $password]);
mysqli_close($bd);
print "L'utilisateur a bien été créé ✅ \n
<a href='../index.php'>Accéder au site.</a>";