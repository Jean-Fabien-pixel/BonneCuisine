<?php
// Types de fichiers autorisés
$arr_file_types = ['image/png', 'image/jpg', 'image/jpeg'];

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
$extension = pathinfo($filename, PATHINFO_EXTENSION);
if ($extension == '.png') {
    $extension = '.png';
} else {
    $extension = '.jpg';
}
$destination = 'uploads/img' . $extension;


// Déplacer le fichier
if (move_uploaded_file($filename, $destination)) {
    echo $filename;  // Renvoyer le chemin du fichier au client
} else {
    echo "Erreur lors du téléchargement.";
}
die;
?>
