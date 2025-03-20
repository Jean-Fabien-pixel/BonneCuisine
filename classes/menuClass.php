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
        $requete->execute(['id' => $this->idMenu,
            'nom' => $nom,
            'description' => $description,
            'prix' => $prix]);

        $image_temporaire = glob("uploads/img.*");
        if (!empty($image_temporaire)) {
            return true;
        } else {
            $this->modifierImage($this->idMenu);
        }

        return true;
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
        // Cherche une image existante dans /uploads/
        $image_temporaire = glob("uploads/img.*"); // Cherche img.png, img.jpg, img.jpeg...

        if (empty($image_temporaire)) {
            return false; // Aucune image trouvée
        }
        // Récupère l'extension
        $extension = pathinfo($image_temporaire[0], PATHINFO_EXTENSION);

        // Définir le chemin final
        $destination = "images/tableMenu_image/{$number}.{$extension}";

        // Déplacer l'image vers sa destination finale
        if (rename($image_temporaire[0], $destination)) {
            return true; // Succès
        }

        return false;
    }


    private function modifierImage($number): bool
    {
        // Supprimer l'ancienne image
        $this->supprimerImage($number);
        // Déplacer le fichier téléchargé vers la destination finale
        $this->ajouterImage($number);

        // Aucun fichier téléchargé, donc on ne fait rien
        return true;
    }

    public function supprimerImage($id): bool
    {
        // Définir les extensions possibles
        $extensions = ['png', 'jpg', 'jpeg'];

        // Vérifier et supprimer l'image avec la bonne extension
        foreach ($extensions as $ext) {
            $fichier = "images/tableMenu_image/{$id}.{$ext}";
            if (file_exists($fichier)) {
                unlink($fichier);
                return true;
            }
        }
        return false;
    }

}