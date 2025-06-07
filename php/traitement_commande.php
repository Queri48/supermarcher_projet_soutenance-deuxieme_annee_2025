<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Validation du numéro de téléphone
$numero = trim($_POST['numero']);
if (!preg_match('/^01[0-9]{8}$/', $numero)) {
    die("Numéro de téléphone invalide. Il doit commencer par 01 et contenir exactement 10 chiffres.");
}


$adresse = trim($_POST['adresse']);
$mode_paiement = $_POST['mode_paiement'];
$montant = (float)$_POST['montant'];
$statut = "en_attente";

// ✅ Étape 1 : Créer la commande
$stmt = $conn->prepare("INSERT INTO commande (id, date_commande, adresse, montant_total, mode_paiement, statut) 
                        VALUES (?, NOW(), ?, ?, ?, ?)");
$stmt->bind_param("isdss", $user_id, $adresse, $montant, $mode_paiement, $statut);
$stmt->execute();
$idcom = $stmt->insert_id;

// ✅ Étape 2 : Récupérer les articles du panier
$sql = "SELECT p.idart, p.quantite, a.prix 
        FROM panier p
        JOIN article a ON a.idart = p.idart
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Étape 3 : Insérer dans detail_commande
while ($row = $result->fetch_assoc()) {
    $stmt2 = $conn->prepare("INSERT INTO detail_commande (idcom, idart, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiid", $idcom, $row['idart'], $row['quantite'], $row['prix']);
    $stmt2->execute();
}

// ✅ Étape 4 : Enregistrer le paiement
$stmt = $conn->prepare("INSERT INTO paiement (idcom, montant, numero_tel, mode_paiement, date_paiement, statut) 
                        VALUES (?, ?, ?, ?, NOW(), ?)");
$stmt->bind_param("idsss", $idcom, $montant, $numero, $mode_paiement, $statut);
$stmt->execute();

// ✅ Étape 5 : Vider le panier
$stmt = $conn->prepare("DELETE FROM panier WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// ✅ Rediriger vers la page de paiement ou de confirmation
header("Location: paiement.php?idcom=" . $idcom);
exit;
?>