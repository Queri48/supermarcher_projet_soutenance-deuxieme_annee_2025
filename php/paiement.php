<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['idcom']) || !is_numeric($_GET['idcom'])) {
    die("Commande invalide.");
}

$idcom = (int)$_GET['idcom'];
$user_id = $_SESSION['user_id'];

// Vérifier que la commande appartient à l'utilisateur
$stmt = $conn->prepare("SELECT * FROM commande WHERE idcom = ? AND id = ?");
$stmt->bind_param("ii", $idcom, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$commande = $result->fetch_assoc();

if (!$commande) {
    die("Commande introuvable.");
}

// Informations de paiement
$stmt = $conn->prepare("SELECT * FROM paiement WHERE idcom = ?");
$stmt->bind_param("i", $idcom);
$stmt->execute();
$paiement = $stmt->get_result()->fetch_assoc();

include 'header.php';
?>

<div class="container py-5">
    <h2>Merci pour votre commande !</h2>
    <p class="lead">Un agent vous contactera pour valider le paiement.</p>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Détails de la commande</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Commande n° <?= $commande['idcom'] ?></li>
                <li class="list-group-item">Montant total : <?= number_format($commande['montant_total'], 0, ',', ' ') ?> FCFA</li>
                <li class="list-group-item">Méthode de paiement : <?= strtoupper($paiement['mode_paiement']) ?></li>
                <li class="list-group-item">Téléphone : <?= htmlspecialchars($paiement['numero_tel']) ?></li>
                <li class="list-group-item">Statut : <?= ucfirst($paiement['statut']) ?></li>
            </ul>
        </div>
    </div>
</div>
