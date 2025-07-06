<?php
session_start();
$title = "Gestion des contacts";
require '../database.php';
require_once '../helpers.php';
include 'header.php';

$stmt = $conn->prepare("SELECT * FROM contact");
$stmt->execute();
$result = $stmt->get_result();
$contacts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><?= e($title) ?></h1>
        <li class="nav-item d-flex align-items-center flex-grow-1 mx-1 order-0" style="max-width: 160px;" id="lirech">
            <form class="input-group w-100" action="catalogue.php" method="GET">
                <input class="form-control" type="search" name="recherche" id="recherche" placeholder="Recherche" aria-label="Search" style="height: 38px;">
                <button class="btn btn-primary d-flex align-items-center justify-content-center" type="submit" style="height: 38px; width: 45px;">
                    <i class="fas fa-search text-white"></i>
                </button>
            </form>
        </li>
    </div>

    <div class="table-responsive shadow-sm bg-white rounded p-3">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nom et Prénom</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date d'envoie</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $index => $user): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= e($user['nom_prenom']) ?></td>
                        <td><?= e($user['email']) ?></td>
                        <td><?= e($user['message']) ?></td>
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