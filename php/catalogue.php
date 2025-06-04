<?php
session_start();
$title = "Bienvenue dans le catalogue Super U";
require 'database.php';
require_once 'helpers.php';
include 'header.php';

// Récupérer les catégories et le nombre de produits par catégorie
$stmt = $conn->prepare("SELECT c.*, COUNT(a.idart) AS nb_produits
                      FROM categorie c
                      LEFT JOIN article a ON c.idcat = a.idcat
                      GROUP BY c.idcat, c.titre, c.resume, c.description, c.image, c.datetime
                      ORDER BY c.datetime ASC");
$stmt->execute();
$result = $stmt->get_result();
$categories = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="container py-5">
    <section class="bg-light p-5 rounded shadow mb-5 text-center">
        <h1 class="display-5 mb-3"><?= $title ?></h1>
        <p class="lead text-muted">
            Chez Super U, nous croyons que faire les courses doit être un plaisir. Notre catalogue vous propose une large
            sélection de produits soigneusement classés par catégories. Vous trouverez tout ce dont vous avez besoin :
            de l'épicerie du quotidien aux produits frais, en passant par les promotions exceptionnelles. Cliquez sur une catégorie
            pour découvrir en détail ce qu’elle contient. La qualité, le choix et le prix juste sont toujours au rendez-vous !
        </p>
    </section>
    <div class="row g-4">
        <?php foreach ($categories as $categorie): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 rounded-4">
                    <a href="produit.php?idart=<?= $categorie['idcat'] ?>" class="text-decoration-none text-dark">
                        <?php if (!empty($categorie['image'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($categorie['image']) ?>" class="card-img-top rounded-top-4" alt="<?= htmlspecialchars($categorie['titre']) ?>" style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary fw-bold text-uppercase"><?= e($categorie['titre']) ?></h5>
                            <p class="card-text text-muted small mb-2"><?= e($categorie['resume']) ?></p>
                            <div class="mt-auto d-flex justify-content-between align-items-center gap-2 flex-wrap">
                                <p class="text-success fw-semibold mb-0">Produits : <?= $categorie['nb_produits'] ?></p>
                                <a href="categorie.php?id=<?= $categorie['idcat'] ?>" class="btn btn-outline-primary btn-sm">Voir les détails</a>
                            </div>

                        </div>
                        <div class="card-footer bg-light border-0 rounded-bottom-4 text-end small text-muted">
                            Ajouté le <?= date('d/m/Y', strtotime($categorie['datetime'])) ?>
                        </div>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($categories)): ?>
            <div class="col-12 text-center text-muted">Aucune catégorie trouvée pour le moment.</div>
        <?php endif; ?>
    </div>
</div>

<style>
    body {
        background: #f7f9fc;
    }

    .card:hover {
        transform: translateY(-5px);
        transition: 0.3s ease-in-out;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
</style>