<?php
session_start();
$title = "Gestion des Promotions";
require '../database.php';
require_once '../helpers.php';
include 'header.php';


$stmt = $conn->prepare(" SELECT p.idpromo,p.titre
                                AS promo_titre, p.description, a.date_debut, a.date_fin, a.pourcentage, ar.idart, ar.titre
                                AS article_titre,c.titre AS categorie_titre
                                FROM  promotion p
                                JOIN  appliquer a  ON p.idpromo = a.idpromo
                                JOIN  article   ar ON a.idart   = ar.idart
                                JOIN  categorie c  ON ar.idcat  = c.idcat
                                ORDER BY p.idpromo DESC");
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$promotions = [];
foreach ($rows as $row) {
    $id = $row['idpromo'];

    if (!isset($promotions[$id])) {
        $promotions[$id] = [
            'idpromo'      => $row['idpromo'],
            'promo_titre'  => $row['promo_titre'],
            'description'  => $row['description'],
            'date_debut'   => $row['date_debut'],
            'date_fin'     => $row['date_fin'],
            'pourcentage'  => $row['pourcentage'],
            'categories'   => [],
            'articles'     => [],
        ];
    }

    if (!in_array($row['categorie_titre'], $promotions[$id]['categories'], true)) {
        $promotions[$id]['categories'][] = $row['categorie_titre'];
    }

    $promotions[$id]['articles'][$row['idart']] = $row['article_titre'];
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><?= e($title) ?></h1>
        <div class="d-flex justify-content-between">
            <li class="nav-item d-flex align-items-center flex-grow-1 mx-1 order-0" style="max-width: 160px;" id="lirech">
                <form class="input-group w-100" action="catalogue.php" method="GET">
                    <input class="form-control" type="search" name="recherche" id="recherche" placeholder="Recherche" aria-label="Search" style="height: 38px;">
                    <button class="btn btn-primary d-flex align-items-center justify-content-center" type="submit" style="height: 38px; width: 45px;">
                        <i class="fas fa-search text-white"></i>
                    </button>
                </form>
            </li>
            <a href="ajouter_promotion.php" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Ajouter une Promotion
            </a>
        </div>
    </div>

    <div class="table-responsive shadow-sm bg-white rounded p-3">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Pourcentage</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Catégories</th>
                    <th>Produits</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_values($promotions) as $index => $promo): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= e($promo['promo_titre']) ?></td>
                        <td><?= e($promo['description']) ?></td>
                        <td><?= e($promo['pourcentage']) ?>%</td>
                        <td><?= e(date('Y-m-d', strtotime($promo['date_debut']))) ?></td>
                        <td><?= e(date('Y-m-d', strtotime($promo['date_fin']))) ?></td>
                        <td>
                            <?php foreach ($promo['categories'] as $cat): ?>
                                <span class="badge bg-info text-dark me-1 mb-1"><?= e($cat) ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php foreach ($promo['articles'] as $idart => $art): ?>
                                <span class="badge bg-info text-dark me-0 mb-1"><?= e($art) ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td class="text-center">
                            <a href="modifier_promotion.php?idpromo=<?= intval($promo['idpromo']) ?>" class="btn btn-sm btn-outline-success me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="supprimer_promotion.php?idpromo=<?= intval($promo['idpromo']) ?>" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($promotions)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">Aucune promotion trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>