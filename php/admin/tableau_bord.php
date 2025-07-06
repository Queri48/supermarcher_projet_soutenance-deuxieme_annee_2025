<?php
session_start();
$title = "Tableau de bord - Super U Bénin";
require '../database.php';
include 'header.php';

// Requêtes pour les statistiques
$nbProduits    = $conn->query("SELECT COUNT(*) FROM article")->fetch_row()[0];
$nbCategories  = $conn->query("SELECT COUNT(*) FROM categorie")->fetch_row()[0];
$nbUtilisateurs = $conn->query("SELECT COUNT(*) FROM utilisateur")->fetch_row()[0];
$nbCommandes   = $conn->query("SELECT COUNT(*) FROM commande")->fetch_row()[0];
?>

<div class="container py-5">
    <h1 class="mb-5 text-center text-primary"><i class="fas fa-tools me-2"></i>Tableau de bord de l'administrateur</h1>

    <!-- Statistiques -->
    <div class="row row-cols-1 row-cols-md-4 g-4 mb-5">
        <div class="col">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-box-open fa-2x"></i>
                    <h5 class="mt-3">Produits</h5>
                    <h4><?= $nbProduits ?></h4>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-th-list fa-2x"></i>
                    <h5 class="mt-3">Catégories</h5>
                    <h4><?= $nbCategories ?></h4>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x"></i>
                    <h5 class="mt-3">Utilisateurs</h5>
                    <h4><?= $nbUtilisateurs ?></h4>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-shopping-cart fa-2x"></i>
                    <h5 class="mt-3">Commandes</h5>
                    <h4><?= $nbCommandes ?></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <a href="ajouter_produit.php" class="btn btn-outline-primary w-100 py-3">
                <i class="fas fa-plus-circle me-1"></i> Ajouter un produit
            </a>
        </div>
        <div class="col-md-3">
            <a href="ajouter_categorie.php" class="btn btn-outline-secondary w-100 py-3">
                <i class="fas fa-folder-plus me-1"></i> Nouvelle catégorie
            </a>
        </div>
        <div class="col-md-3">
            <a href="ajouter_promotion.php" class="btn btn-outline-danger w-100 py-3">
                <i class="fas fa-tags me-1"></i> Ajouter une promotion
            </a>
        </div>
        <div class="col-md-3">
            <a href="commandes.php" class="btn btn-outline-success w-100 py-3">
                <i class="fas fa-file-invoice me-1"></i> Voir les commandes
            </a>
        </div>
    </div>
</div>
<?php

// Total utilisateurs
$total_users = $conn->query("SELECT COUNT(*) FROM utilisateur")->fetch_row()[0];

// Total articles
$total_articles = $conn->query("SELECT COUNT(*) FROM article")->fetch_row()[0];

// Total commandes
$total_commandes = $conn->query("SELECT COUNT(*) FROM commande")->fetch_row()[0];

// Produits les plus vendus (Top 5)
$produits_vendus = $conn->query("
    SELECT a.titre, SUM(dc.quantite) as total
    FROM details_commande dc
    JOIN article a ON dc.idart = a.idart
    GROUP BY a.titre
    ORDER BY total DESC
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Commandes par mois (12 derniers mois)
$commandes_par_mois = $conn->query("
    SELECT DATE_FORMAT(date_commande, '%Y-%m') as mois, COUNT(*) as total
    FROM commande
    GROUP BY mois
    ORDER BY mois DESC
    LIMIT 12
")->fetch_all(MYSQLI_ASSOC);
?>

<div class="container py-5">
    <!-- Graphiques -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="bg-white p-3 shadow rounded">
                <h5 class="text-center">Top 5 Produits les plus vendus</h5>
                <canvas id="topProduits"></canvas>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="bg-white p-3 shadow rounded">
                <h5 class="text-center">Commandes par mois</h5>
                <canvas id="commandesMois"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const produitsData = {
        labels: <?= json_encode(array_column($produits_vendus, 'titre')) ?>,
        datasets: [{
            label: 'Quantité vendue',
            data: <?= json_encode(array_column($produits_vendus, 'total')) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    const produitsConfig = {
        type: 'bar',
        data: produitsData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    };

    new Chart(document.getElementById('topProduits'), produitsConfig);

    const commandesData = {
        labels: <?= json_encode(array_column($commandes_par_mois, 'mois')) ?>.reverse(),
        datasets: [{
            label: 'Commandes',
            data: <?= json_encode(array_column($commandes_par_mois, 'total')) ?>.reverse(),
            fill: false,
            borderColor: 'rgba(255,99,132,1)',
            tension: 0.1
        }]
    };

    const commandesConfig = {
        type: 'line',
        data: commandesData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    };

    new Chart(document.getElementById('commandesMois'), commandesConfig);
});
</script>

