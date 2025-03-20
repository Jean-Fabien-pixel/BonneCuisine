<?php

class menuClass
{
    private $idMenu;
    private $nom;
    private $prix;
    private $description;

    public function __construct()
    {
        $cpt = func_num_args();
        $args = func_get_args();

        if ($cpt >= 3) {
            $this->setNom($args[0]);
            $this->setPrix($args[2]);
            $this->setDescription($args[1]);
        }
        if ($cpt == 1) {
            $this->setIdMenu($args[0]);
        }
    }


    public function getIdMenu()
    {
        return $this->idMenu;
    }

    public function setIdMenu($idMenu)
    {
        $this->idMenu = $idMenu;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function ajouterMenuBD($bd, $table): bool
    {
        if (!in_array($table, ["menu_fr", "menu_en"])) {
            return false;
        }
        $requete = $bd->prepare("INSERT INTO $table (nom, description, prix) values(:nom, :description, :prix)");
        if (!$requete->execute([
            'nom' => $this->nom,
            'description' => $this->description,
            'prix' => $this->prix
        ])) {
            return false;
        }

        $number = $bd->lastInsertId();
        if ($number > 0 && $this->ajouterImage($number)) {
            return true;
        }
        return false;
    }

    public function modifierMenuBD($bd, $nom, $description, $prix, $table): bool
    {
        $requete = $bd->prepare("UPDATE $table SET nom=:nom, description=:description, prix=:prix WHERE idMenu=:id");
        $resultat = $requete->execute([
            'id' => $this->idMenu,
            'nom' => $nom,
            'description' => $description,
            'prix' => $prix
        ]);

        if (!$resultat) {
            var_dump($requete->errorInfo()); // Affiche l'erreur SQL si la requête échoue
        }

        return $resultat;
    }

    public function supprimerMenuBD(PDO $bd): bool
    {
        $id = $this->idMenu;

        $deletePanier = $bd->prepare("DELETE FROM panier WHERE noProduit=:id");
        $delete1 = $bd->prepare("DELETE FROM menu_fr WHERE idMenu=:id");
        $delete2 = $bd->prepare("DELETE FROM menu_en WHERE idMenu=:id");

        if ($deletePanier->execute(['id' => $id]) && $delete1->execute(['id' => $id]) && $delete2->execute(['id' => $id]) &&
            $this->supprimerImage($id)) {
            return true;
        }
        return false;
    }

    private function ajouterImage($number): bool
    {
        $filename = $_FILES['file']['name'];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if ($extension == '.png') {
            $extension = '.png';
        } else {
            $extension = '.jpg';
        }

        $fichier = 'uploads/img' . $extension;  // Récupérer le nom de l'image depuis le formulaire
        $destination = "images/tableMenu_image/{$number}.jpg";  // Renommer avec l'ID du menu


        if (file_exists($fichier)) {
            rename($fichier, $destination);
        } else {
            return false;  // Erreur : image non trouvée
        }
        return true;
    }

    private function modifierImage(): bool
    {
        if ($_FILES["imageMenu"]) {
            $fichier = $_FILES["imageMenu"]["tmp_name"];
            $destination = "images/tableMenu_image/$this->idMenu.png";
            unlink($destination);
            if (move_uploaded_file($fichier, $destination)) {
                return true;
            }
            return true;
        }
        return false;
    }

    public function supprimerImage($id): bool
    {
        // Supprimer l'image associée
        $destination = "images/tableMenu_image/{$id}.png";
        if (file_exists($destination)) {
            unlink($destination);
        }
        return true;
    }

}