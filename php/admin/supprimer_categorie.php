<?php
session_start();
$title = "Confirmer la suppression";
require '../database.php';
require_once '../helpers.php';

$id = $_GET['idcat'] ?? null;

if (!$id) {
    header("Location: categorie.php");
    exit;
}

// Récupérer l'utilisateur pour affichage
$stmt = $conn->prepare("SELECT * FROM categorie WHERE idcat = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$categorie = $result->fetch_assoc();

if (!$categorie) {
    header("Location: categorie.php");
    exit;
}

// Si l'utilisateur confirme la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM categorie WHERE idcat = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: categorie.php");
    exit;
}
include '../header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white p-4 rounded shadow">
            <h4 class="mb-4 text-danger text-center"><?= $title ?></h4>
            <p class="text-center">Êtes-vous sûr de vouloir supprimer la categorie suivant ?</p>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Titre :</strong> <?= e($categorie['titre']) ?></li>
                <li class="list-group-item"><strong>Resumé :</strong> <?= e($categorie['resume']) ?></li>
                <li class="list-group-item"><strong>Description :</strong> <?= e($categorie['description']) ?></li>
            </ul>
            <form method="POST">
                <div class="d-flex justify-content-between">
                    <a href="categorie.php" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>