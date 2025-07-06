<?php
session_start();
$title = "Accueil - Super U Bénin";
require 'database.php';
require_once 'helpers.php';
include 'header.php';

// Récupération dynamique des catégories
$catStmt = $conn->query("SELECT * FROM categorie LIMIT 6");
$categories = $catStmt->fetch_all(MYSQLI_ASSOC);

// Produits populaires (ex. : ceux qui ont été le plus commandés)
$popStmt = $conn->query(" SELECT a.*, COUNT(dc.idart) AS nb_commande
    FROM article a
    JOIN details_commande dc ON a.idart = dc.idart
    GROUP BY a.idart
    ORDER BY nb_commande DESC
    LIMIT 6
");
$produits_populaires = $popStmt->fetch_all(MYSQLI_ASSOC);

// Promotions en cours
$promoStmt = $conn->query(" SELECT a.*, ap.pourcentage
    FROM article a
    JOIN appliquer ap ON a.idart = ap.idart
    WHERE NOW() BETWEEN ap.date_debut AND ap.date_fin
    ORDER BY ap.date_debut DESC
    LIMIT 6
");
$promotions = $promoStmt->fetch_all(MYSQLI_ASSOC);
?>

<div class="position-relative mb-5">
    <div id="customHeroCarousel" class="carousel-container" style="height: 450px; overflow: hidden; position: relative;">
        <div id="carouselImageContainer" class="w-100 h-100"></div>
        <div class="overlay position-absolute top-0 start-0 w-100 h-100"></div>
        <div class="carousel-caption text-white text-center position-absolute w-51" style="bottom: 100px;">
            <h1 class="display-4 fw-bold">Bienvenue chez Super U Bénin</h1>
            <p class="lead">Vos courses, simples, rapides et livrées à domicile !</p>
            <a href="catalogue.php" class="btn btn-superu btn-lg mt-3 fw-bold">
                <i class="fas fa-shopping-basket me-2"></i>Voir le catalogue
            </a>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row flex-nowrap overflow-auto g-4">
        <?php foreach ($categories as $cat): ?>
            <div class="col-6 col-md-2 text-center">
                <a href="catalogue.php?cat=<?= $cat['idcat'] ?>" class="text-decoration-none text-dark">
                    <img src="data:image/jpeg;base64,<?= base64_encode($cat['image']) ?>" class="img-fluid rounded shadow-sm" alt="<?= e($cat['titre']) ?>" />
                    <h5 class="mt-2"><?= e($cat['titre']) ?></h5>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<div class="container mb-5">
    <h2 class="text-center mb-2 text-superu">Produits les Plus Commandés</h2>
    <p class="text-center text-muted mb-4">
        Nos clients les adorent ! Découvrez les articles les plus populaires de notre supermarché.
    </p>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($produits_populaires as $prod): ?>
            <div class="col">
                <div class="card h-100 text-center shadow-sm">
                    <img src="data:image/jpeg;base64,<?= base64_encode($prod['image']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= e($prod['titre']) ?></h5>
                        <p class="card-text"><?= e($prod['resume']) ?></p>
                        <a href="produit.php?id=<?= $prod['idart'] ?>" class="btn btn-outline-success">
                            <i class="fas fa-shopping-cart"></i> Commander
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="container mb-5">
    <h2 class="text-center mb-2 text-superu">Actuellement en Promotion</h2>
    <p class="text-center text-muted mb-4">
        Ne manquez pas nos offres spéciales du moment ! Faites des économies sur vos produits préférés.
    </p>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($promotions as $promo): ?>
            <div class="col">
                <div class="card h-100 text-center shadow-sm border-danger">
                    <img src="data:image/jpeg;base64,<?= base64_encode($promo['image']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= e($promo['titre']) ?> <span class="badge bg-danger"><?= $promo['pourcentage'] ?>%</span></h5>
                        <p class="card-text"><?= e($promo['description']) ?></p>
                        <a href="produit.php?id=<?= $promo['idart'] ?>" class="btn btn-outline-danger">
                            <i class="fas fa-tags"></i> Profiter
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<div class="container mb-5">
    <h2 class="text-center mb-4  text-superu">Nos Services</h2>
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="p-3 bg-white shadow-sm rounded">
                <i class="fas fa-truck fa-2x text-primary"></i>
                <h5 class="mt-2">Livraison Express</h5>
                <p>À domicile ou en point relais</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="p-3 bg-white shadow-sm rounded">
                <i class="fas fa-lock fa-2x text-success"></i>
                <h5 class="mt-2">Paiement Sécurisé</h5>
                <p>Mobile Money, Carte ou Espèces</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="p-3 bg-white shadow-sm rounded">
                <i class="fas fa-headset fa-2x text-info"></i>
                <h5 class="mt-2">Support 7j/7</h5>
                <p>Assistance rapide et efficace</p>
            </div>
        </div>
    </div>
</div>