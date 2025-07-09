<?php
session_start();
require '../database.php';

if (!isset($_GET['idcmd']) || !is_numeric($_GET['idcmd'])) {
    die("Commande invalide.");
}

$idcmd = (int)$_GET['idcmd'];

$stmt = $conn->prepare("SELECT * FROM paiement WHERE idcmd = ?");
$stmt->bind_param("i", $idcmd);
$stmt->execute();
$paiement = $stmt->get_result()->fetch_assoc();

if (!$paiement || $paiement['statut'] !== 'en attente') {
    die("La commande ne peut plus être annulée.");
}

// 1. Mettre à jour le statut de la commande
$stmt = $conn->prepare("UPDATE commande SET statut = 'annulée' WHERE idcmd = ?");
$stmt->bind_param("i", $idcmd);
$stmt->execute();

$stmt = $conn->prepare("UPDATE paiement SET statut = 'annulé' WHERE idcmd = ?");
$stmt->bind_param("i", $idcmd);
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM details_commande WHERE idcmd = ?");
$stmt->bind_param("i", $idcmd);
$stmt->execute();

header("Location: commande.php?annulation=success");
exit;
?>
