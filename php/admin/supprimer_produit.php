<?php
session_start();
$title = "Confirmer la suppression";
require '../database.php';
require_once '../helpers.php';

$id = $_GET['idart'] ?? null;

if (!$id) {
    header("Location: produit.php");
    exit;
}

// Récupérer l'utilisateur pour affichage
$stmt = $conn->prepare("SELECT * FROM article WHERE idart = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

if (!$article) {
    header("Location: produit.php");
    exit;
}

// Si l'utilisateur confirme la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM article WHERE idart = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: produit.php");
    exit;
}
include '../header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white p-4 rounded shadow">
            <h4 class="mb-4 text-danger text-center"><?= $title ?></h4>
            <p class="text-center">Êtes-vous sûr de vouloir supprimer la article suivant ?</p>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Titre :</strong> <?= e($article['titre']) ?></li>
                <li class="list-group-item"><strong>Resumé :</strong> <?= e($article['resume']) ?></li>
                <li class="list-group-item"><strong>Description :</strong> <?= e($article['description']) ?></li>
            </ul>
            <form method="POST">
                <div class="d-flex justify-content-between">
                    <a href="produit.php" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>