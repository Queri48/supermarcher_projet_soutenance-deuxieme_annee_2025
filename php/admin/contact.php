<?php
session_start();
$title = "Gestion des contacts";
include '../header.php';
require '../database.php';

$stmt = $conn->prepare("SELECT * FROM contact");
$stmt->execute();
$result = $stmt->get_result();
$contacts = $result->fetch_all(MYSQLI_ASSOC);
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
                    <th>Message</th>
                    <th>Date de création</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $index => $user): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['prenom']) ?></td>
                        <td><?= htmlspecialchars($user['message']) ?></td>
                        <td><?= date('Y-m-d H:i:s', strtotime($user['datetime'])) ?></td>
                        <td class="text-center">
                            <a href="supprimer_contact.php?idcont=<?= $user['idcont'] ?>" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($contacts)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Aucun contact trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>