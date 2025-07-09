<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Validation du numéro
$numero = trim($_POST['numero']);
if (!preg_match('/^01[0-9]{8}$/', $numero)) {
    die("Numéro de téléphone invalide. Il doit commencer par 01 et contenir exactement 10 chiffres.");
}

$adresse = trim($_POST['adresse_livraison']);
$mode_paiement = $_POST['mode_paiement'];
$montant = (float)$_POST['montant_total'];
$statut_commande = "en attente"; // ✅ match ENUM
$statut_paiement = "en attente"; // ✅ match ENUM

// 1. Insérer la commande
$stmt = $conn->prepare("INSERT INTO commande (id, date_commande, statut, mode_paiement, numero, montant_total, adresse_livraison)
                        VALUES (?, NOW(), ?, ?, ?, ?, ?)");
$stmt->bind_param("isssds", $user_id, $statut_commande, $mode_paiement, $numero, $montant, $adresse);
$stmt->execute();
$idcom = $stmt->insert_id;

// 2. Enregistrer le paiement
$stmt = $conn->prepare("INSERT INTO paiement (idcmd, methode_paiement, statut, date_paiement, montant, numero, transaction_id)
                        VALUES (?, ?, ?, NOW(), ?, ?, '')");
$stmt->bind_param("issds", $idcom, $mode_paiement, $statut_paiement, $montant, $numero);
$stmt->execute();

// 3. Récupérer les articles du panier
$sql = "SELECT p.idart, p.quantite, a.prix 
        FROM panier p
        JOIN article a ON a.idart = p.idart
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// 4. Enregistrer les détails de la commande
while ($row = $result->fetch_assoc()) {
    $stmt2 = $conn->prepare("INSERT INTO details_commande (idcmd, idart, quantite, prix_unitaire)
                             VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiid", $idcom, $row['idart'], $row['quantite'], $row['prix']);
    $stmt2->execute();
}

// 5. Vider le panier
$stmt = $conn->prepare("DELETE FROM panier WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// 6. Redirection
header("Location: paiement.php?idcmd=" . $idcom);
exit;
?>
