<?php
session_start();
require 'database.php';
require_once 'helpers.php';
include 'header.php';

if (!isset($_GET['idart']) || !is_numeric($_GET['idart'])) {
    echo "<div class='alert alert-danger text-center'>Catégorie invalide.</div>";
    exit;
}

$idcat = intval($_GET['idart']);

// Récupérer les infos de la catégorie
$stmt = $conn->prepare("SELECT titre, description FROM categorie WHERE idcat = ?");
$stmt->bind_param("i", $idcat);
$stmt->execute();
$categorie = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$categorie) {
    echo "<div class='alert alert-warning text-center'>Cette catégorie n'existe pas.</div>";
    exit;
}

// Récupérer les produits
$stmt = $conn->prepare("SELECT idart, titre, resume, prix, quantite_stock, image, datetime FROM article WHERE idcat = ?");
$stmt->bind_param("i", $idcat);
$stmt->execute();
$result = $stmt->get_result();
$produits = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$title = "Produits - " . e($categorie['titre']);
?>

<div class="container py-5">
    <section class="bg-white p-5 rounded shadow-sm mb-5 text-center">
        <h1 class="display-5 text-primary"><?= e($categorie['titre']) ?></h1>
        <p class="lead text-muted"><?= e($categorie['description']) ?></p>
    </section>

    <div class="row g-4">
        <?php foreach ($produits as $produit): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-column">
                    <?php if (!empty($produit['image'])): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($produit['image']) ?>"
                             class="card-img-top" alt="<?= e($produit['titre']) ?>"
                             style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-dark fw-bold"><?= e($produit['titre']) ?></h5>
                        <p class="card-text text-muted small mb-2"><?= e($produit['resume']) ?></p>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="badge bg-success">Prix : <?= number_format($produit['prix'], 0, ',', ' ') ?> FCFA</span>
                            <span class="badge bg-info text-dark">Stock : <?= $produit['quantite_stock'] ?></span>
                        </div>

                        <div class="mt-auto d-flex justify-content-between align-items-center gap-2 flex-wrap">
                            <a href="detail_produit.php?idart=<?= $produit['idart'] ?>" class="btn btn-outline-primary btn-sm w-45">
                                <i class="fas fa-info-circle me-1"></i> Détails
                            </a>
                            <a href="ajouter_panier.php?idart=<?= $produit['idart'] ?>" class="btn btn-outline-success btn-sm w-45">
                                <i class="fas fa-shopping-cart me-1"></i> Panier
                            </a>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-0 text-end small text-muted">
                        Ajouté le <?= date('d/m/Y', strtotime($produit['datetime'])) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($produits)): ?>
            <div class="col-12 text-center text-muted">Aucun produit disponible dans cette catégorie.</div>
        <?php endif; ?>
    </div>
</div>

<style>
    body {
        background: #f8fafc;
    }

    .card {
        transition: all 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.4em 0.6em;
    }

    .card-footer {
        background-color: #f0f4f8;
    }
</style>
