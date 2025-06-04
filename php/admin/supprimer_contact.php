<?php
session_start();
$title = "Confirmer la suppression";
require '../database.php';
require_once '../helpers.php';

$id = $_GET['idcont'] ?? null;

if (!$id) {
    header("Location: contact.php");
    exit;
}

// Récupérer l'utilisateur pour affichage
$stmt = $conn->prepare("SELECT * FROM contact WHERE idcont = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$contact = $result->fetch_assoc();

if (!$contact) {
    header("Location: contact.php");
    exit;
}

// Si l'utilisateur confirme la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM contact WHERE idcont = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: contact.php");
    exit;
}
include '../header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white p-4 rounded shadow">
            <h4 class="mb-4 text-danger text-center"><?= $title ?></h4>
            <p class="text-center">Êtes-vous sûr de vouloir supprimer le contact suivant ?</p>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Nom :</strong> <?= e($contact['nom']) ?></li>
                <li class="list-group-item"><strong>Prénom :</strong> <?= e($contact['prenom']) ?></li>
                <li class="list-group-item"><strong>message :</strong> <?= e($contact['message']) ?></li>
            </ul>
            <form method="POST">
                <div class="d-flex justify-content-between">
                    <a href="contact.php" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>
