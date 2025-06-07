<?php
session_start();
require 'database.php';
require_once 'helpers.php';

if (!isset($_GET['idart']) || !is_numeric($_GET['idart'])) {
    header('Location: index.php');
    exit;
}

$idart = intval($_GET['idart']);
$quantite = 1;

// Récupération de l'article
$stmt = $conn->prepare("SELECT idart, titre, prix, image FROM article WHERE idart = ?");
$stmt->bind_param("i", $idart);
$stmt->execute();
$result = $stmt->get_result();
$produit = $result->fetch_assoc();

if (!$produit) {
    header('Location: index.php');
    exit;
}

if (isset($_SESSION['user_id']) ) {
    $id = intval($_SESSION['user_id']); // Récupération fiable de l'id utilisateur

    // Vérifier si l'article est déjà dans le panier
    $check = $conn->prepare("SELECT idpan, quantite FROM panier WHERE id = ? AND idart = ?");
    $check->bind_param("ii", $id, $idart);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $newQuantite = $row['quantite'] + 1;
        $update = $conn->prepare("UPDATE panier SET quantite = ? WHERE idpan = ?");
        $update->bind_param("ii", $newQuantite, $row['idpan']);
        $update->execute();
    } else {
        $now = date('Y-m-d H:i:s');
        $insert = $conn->prepare("INSERT INTO panier (id, idart, quantite, date_ajout) VALUES (?, ?, ?, ?)");
        $insert->bind_param("iiis", $id, $idart, $quantite, $now);
        $insert->execute();
    }

    $check->close();
    $res->free();
} else {
    // Utilisateur non connecté
    if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];

    $found = false;
    foreach ($_SESSION['panier'] as &$item) {
        if ($item['idart'] == $idart) {
            $item['quantite'] += 1;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['panier'][] = [
            'idart' => $idart,
            'quantite' => 1
        ];
    }
}

$stmt->close();

// Redirection
$referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . e($referer));
exit;
