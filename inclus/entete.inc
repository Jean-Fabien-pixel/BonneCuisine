<!DOCTYPE html>
<html lang="<?= $_COOKIE["lang"] ?? 'fr'; ?>">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>La Bonne Cuisine</title>
    <link rel="shortcut icon" href="./images/cuisine.ico" type="image/x-icon"/>
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/style.css"/>
    <script src="./js/script.js"></script>
</head>

<body onload="AfficherDate()">
<div class="container">
    <div class="row card">
        <div class="card-body bg-primary">
            <div class="text-end">
                <a class="text-decoration-none text-light fw-bold" href="?lang=fr"
                >Français</a>
                <strong class="text-light">/</strong>
                <a class="text-decoration-none text-light fw-bold" href="?lang=en"
                >English</a>
            </div>
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand text-light" href="index.php"
                    ><img src="images/logoBonneCuisine.png" height="80" width="210" alt="Logo Bonne Cuisine"
                        /></a>
                    <button
                            class="navbar-toggler"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#navbarNavAltMarkup"
                            aria-controls="navbarNavAltMarkup"
                            aria-expanded="false"
                            aria-label="Toggle navigation"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav">
                            <a
                                    class="nav-link text-light fw-bold"
                                    aria-current="page"
                                    href="menu.php"
                            ><?= $translations["header_menu"] ?></a
                            >
                            <a class="nav-link text-light fw-bold"
                               href="commande.php"><?= $translations["header_commande"] ?></a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <div class="text-center" id="date"></div>
    <div class="row p-3 mt-1 border" style="text-align: justify">