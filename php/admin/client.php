<?php
session_start();
$title = "Gestion des clients";
require '../database.php';
require_once '../helpers.php';
include '../header.php';

// Exemple de récupération d'utilisateurs
$stmt = $conn->prepare("SELECT id, nom, prenom, email, tel, adresse, role, datetime FROM utilisateur WHERE role = 2");
$stmt->execute();
$result = $stmt->get_result();
$utilisateurs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><?= $title ?></h1>
    </div>

    <div class="table-responsive shadow-sm bg-white rounded p-3">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Rôle</th>
                    <th>Date de création</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $index => $user): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= e($user['nom']) ?></td>
                        <td><?= e($user['prenom']) ?></td>
                        <td><?= e($user['email']) ?></td>
                        <td><?= e($user['tel']) ?></td>
                        <td><?= e($user['adresse']) ?></td>
                        <td>
                            <?php
                            switch ($user['role']) {
                                case 0:
                                    echo '<span class="badge bg-dark">Administrateur</span>';
                                    break;
                                case 1:
                                    echo '<span class="badge bg-primary">Employé</span>';
                                    break;
                                case 2:
                                    echo '<span class="badge bg-secondary">Client</span>';
                                    break;
                                default:
                                    echo '<span class="badge bg-light text-dark">Inconnu</span>';
                            }
                            ?>
                        </td>
                        <td><?= date('Y-m-d H:i:s', strtotime($user['datetime'])) ?></td>
                        <td class="text-center">
                            <a href="modifier_client.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-success me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="supprimer_client.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($utilisateurs)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">Aucun client trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>