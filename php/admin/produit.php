<?php
session_start();
$title = "Gestion des Articles";
include '../header.php';
require '../database.php';

$stmt = $conn->prepare("SELECT a.*, c.titre AS categorie_titre FROM article a JOIN categorie c ON a.idcat = c.idcat");
$stmt->execute();
$result = $stmt->get_result();
$articles = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><?= $title ?></h1>
        <a href="ajouter_produit.php" class="btn btn-primary">
            <i class="fas fa-user-plus me-1"></i> Ajouter un Produit
        </a>
    </div>

    <div class="table-responsive shadow-sm bg-white rounded p-3">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Resume</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Quantite en Stock</th>
                    <th>Date de création</th>
                    <th>Catégorie</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $index => $user): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($user['image']) ?></td>
                        <td><?= htmlspecialchars($user['titre']) ?></td>
                        <td><?= htmlspecialchars($user['resume']) ?></td>
                        <td><?= htmlspecialchars($user['description']) ?></td>
                        <td><?= htmlspecialchars($user['prix']) ?></td>
                        <td><?= htmlspecialchars($user['quantite_stock']) ?></td>
                        <td><?= htmlspecialchars($user['categorie_titre']) ?></td>
                        <td><?= date('Y-m-d H:i:s', strtotime($user['datetime'])) ?></td>
                        <td class="text-center">
                            <a href="modifier_categorie.php?id=<?= $user['idcat'] ?>" class="btn btn-sm btn-outline-success me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="supprimer_categorie.php?id=<?= $user['idcat'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($articles)): ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted">Aucun categorie trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>