<?php
session_start();
$title = "Confirmer la suppression";
require '../database.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: administrateur.php");
    exit;
}

// Récupérer l'utilisateur pour affichage
$stmt = $conn->prepare("SELECT * FROM utilisateur WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$utilisateur = $result->fetch_assoc();

if (!$utilisateur) {
    header("Location: administrateur.php");
    exit;
}

// Si l'utilisateur confirme la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM utilisateur WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: administrateur.php");
    exit;
}
include '../header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white p-4 rounded shadow">
            <h4 class="mb-4 text-danger text-center"><?= $title ?></h4>
            <p class="text-center">Êtes-vous sûr de vouloir supprimer l'utilisateur suivant ?</p>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Nom :</strong> <?= htmlspecialchars($utilisateur['nom']) ?></li>
                <li class="list-group-item"><strong>Prénom :</strong> <?= htmlspecialchars($utilisateur['prenom']) ?></li>
                <li class="list-group-item"><strong>Email :</strong> <?= htmlspecialchars($utilisateur['email']) ?></li>
            </ul>
            <form method="POST">
                <div class="d-flex justify-content-between">
                    <a href="administrateur.php" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>
