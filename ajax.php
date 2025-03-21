<?php
// Types de fichiers autorisés
$arr_file_types = ['image/png', 'image/jpg', 'image/jpeg'];

// Vérifier si un fichier a été envoyé
if (!isset($_FILES['imageMenu'])) {
    echo "Aucun fichier reçu.";
    die;
}

if (!(in_array($_FILES['imageMenu']['type'], $arr_file_types))) {
    echo "Format de fichier non autorisé.";
    die;
}

// Vérifier que le dossier 'uploads' existe, sinon le créer
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Générer un nom
$filename = $_FILES['imageMenu']['name'];
$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
$destination = 'uploads/img.' . $extension;


// Déplacer le fichier
if (move_uploaded_file($_FILES['imageMenu']['tmp_name'], $destination)) {
    echo "Succès du téléversement";
} else {
    echo "Erreur lors du téléversement.";
}
die;
?>
