<?php
session_start();
require 'database.php';

$idpan = (int) $_GET['idpan'];

if (isset($_SESSION['user_id'])) {
    // ✅ Utilisateur connecté : suppression dans la base de données
    $user_id = $_SESSION['user_id'];

    // Vérifie que ce panier appartient bien à l'utilisateur
    $verif = $conn->prepare("SELECT 1 FROM panier WHERE idpan = ? AND id = ?");
    $verif->bind_param("ii", $idpan, $user_id);
    $verif->execute();
    $result = $verif->get_result();

    if ($result->num_rows === 0) {
        echo "Ce produit n'existe pas ou ne vous appartient pas.";
        exit;
    }

    // Supprimer la ligne du panier
    $stmt = $conn->prepare("DELETE FROM panier WHERE idpan = ? AND id = ?");
    $stmt->bind_param("ii", $idpan, $user_id);

    if (!$stmt->execute()) {
        echo "Erreur SQL lors de la suppression : " . $stmt->error;
        exit;
    }

} else {
    // ✅ Utilisateur invité : suppression dans la session
    if (isset($_SESSION['panier'][$idpan])) {
        unset($_SESSION['panier'][$idpan]);
    } else {
        echo "Article non trouvé dans votre panier.";
        exit;
    }
}

// ✅ Redirection vers la page précédente ou vers index
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
