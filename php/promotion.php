<?php
session_start();
$title = "Promotions en cours - Super U Bénin";
require 'database.php';
require_once 'helpers.php';
include 'header.php';

// Récupération des promotions en cours
$sql = "SELECT a.*, ap.pourcentage, 
               ROUND(a.prix - (a.prix * ap.pourcentage / 100)) AS nouveau_prix
        FROM article a
        JOIN appliquer ap ON a.idart = ap.idart
        WHERE NOW() BETWEEN ap.date_debut AND ap.date_fin
        ORDER BY ap.date_debut DESC";
$result = $conn->query($sql);
$promotions = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="container py-5">
    <section class="bg-light p-5 rounded shadow mb-5 text-center">
        <h1 class="display-5 mb-3 text-superu fw-bold"><?= $title ?></h1>
    <p class="text-center mb-5">Profitez de nos offres limitées sur vos produits préférés. Ne ratez pas l’occasion de faire des économies !</p>
    </section>

    <?php if (empty($promotions)) : ?>
        <div class="alert alert-info text-center">Aucune promotion n’est disponible pour le moment.</div>
    <?php else : ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php foreach ($promotions as $promo) : ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border border-danger">
                        <img src="data:image/jpeg;base64,<?= base64_encode($promo['image']) ?>" class="card-img-top" style="height: 220px; object-fit: cover;" alt="<?= e($promo['titre']) ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= e($promo['titre']) ?></h5>
                            <p class="text-danger fw-bold mb-1"><?= $promo['pourcentage'] ?>% de réduction</p>
                            <p class="text-muted">
                                <span class="text-decoration-line-through"><?= number_format($promo['prix'], 0, ',', ' ') ?> FCFA</span>
                                <span class="fw-bold text-success ms-2"><?= number_format($promo['nouveau_prix'], 0, ',', ' ') ?> FCFA</span>
                            </p>
                            <a href="produit.php?id=<?= $promo['idart'] ?>" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-tags me-1"></i> Voir le produit
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>