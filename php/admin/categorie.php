<?php
session_start();
$title = "Gestion des Catégories";
require_once '../helpers.php';
require '../database.php';
include 'header.php';

$stmt = $conn->prepare("SELECT * FROM categorie ORDER BY titre asc");
$stmt->execute();
$result = $stmt->get_result();
$categories = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><?= e($title) ?></h1>
        <div class="d-flex justify-content-between">
            <a href="ajouter_categorie.php" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Ajouter une Catégorie
            </a>
        </div>
    </div>

    <div class="mb-3">
        <input type="text" id="rechercheCode" class="form-control" placeholder="Rechercher par catégorie...">
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
                    <th>Date de création</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $index => $user): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>
                            <?php if (!empty($user['image'])): ?>
                                <img src="data:image/jpeg;base64,<?= e(base64_encode($user['image'])) ?>" alt="Image" style="max-width: 100px; max-height: 100px;">
                            <?php else: ?>
                                <span class="text-muted">Aucune image</span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($user['titre']) ?></td>
                        <td><?= e($user['resume']) ?></td>
                        <td><?= e($user['description']) ?></td>
                        <td><?= e(date('Y-m-d H:i:s', strtotime($user['datetime']))) ?></td>
                        <td class="text-center">
                            <a href="modifier_categorie.php?idcat=<?= $user['idcat'] ?>" class="btn btn-sm btn-outline-success me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="supprimer_categorie.php?idcat=<?= $user['idcat'] ?>" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Aucun categorie trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('rechercheCode').addEventListener('input', function() {
        let filter = this.value.trim().toLowerCase();
        let lignes = document.querySelectorAll('table tbody tr');

        lignes.forEach(function(row) {
            let code = row.querySelector('td:nth-child(3)').innerText.toLowerCase();
            if (filter === '' || code.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>