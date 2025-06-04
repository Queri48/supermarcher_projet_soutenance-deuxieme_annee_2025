<?php
session_start();
require 'database.php';  // connexion $conn
require_once 'helpers.php';

if (!isset($_GET['idart']) || !is_numeric($_GET['idart'])) {
    header('Location: index.php'); // ou page d'accueil
    exit;
}

$idart = (int)$_GET['idart'];
$quantite = 1; // Par défaut, on ajoute 1 unité

// Récupérer les infos produit (titre, prix, image...) depuis la base
$stmt = $conn->prepare("SELECT idart, titre, prix, image FROM article WHERE idart = ?");
$stmt->bind_param("i", $idart);
$stmt->execute();
$produit = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$produit) {
    // Produit inexistant
    header('Location: index.php');
    exit;
}

if (isset($_SESSION['user_id'])) {
    // Utilisateur connecté => ajouter en base dans la table panier

    $iduser = $_SESSION['user_id']['id'];

    // Vérifier si produit déjà dans panier en base
    $stmt = $conn->prepare("SELECT quantite FROM panier WHERE id = ? AND idart = ?");
    $stmt->bind_param("ii", $iduser, $idart);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Met à jour la quantité
        $row = $result->fetch_assoc();
        $newQuantite = $row['quantite'] + $quantite;
        $stmt->close();

        $stmt = $conn->prepare("UPDATE panier SET quantite = ? WHERE id = ? AND idart = ?");
        $stmt->bind_param("iii", $newQuantite, $iduser, $idart);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insère un nouveau produit dans panier
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO panier (id, idart, quantite) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $iduser, $idart, $quantite);
        $stmt->execute();
        $stmt->close();
    }

} else {
    // Non connecté => panier dans session

    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // Vérifier si produit existe déjà dans panier session
    $found = false;
    foreach ($_SESSION['panier'] as &$item) {
        if ($item['idart'] == $idart) {
            $item['quantite'] += $quantite;
            $found = true;
            break;
        }
    }
    unset($item);

    if (!$found) {
        // Ajouter nouveau produit au panier session avec toutes les infos nécessaires
        $_SESSION['panier'][] = [
            'idart' => $produit['idart'],
            'titre' => $produit['titre'],
            'prix' => $produit['prix'],
            'image' => $produit['image'],  // blob image pour affichage base64
            'quantite' => $quantite
        ];
    }
}

// Redirection vers la page précédente ou panier
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
