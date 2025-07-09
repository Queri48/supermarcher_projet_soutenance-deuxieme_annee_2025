<?php
require '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idcmd'])) {
    $idcmd = (int)$_POST['idcmd'];
    $code = random_int(1000000000, 9999999999);

    // Mise à jour du paiement + génération du code de retrait
    $stmt = $conn->prepare("UPDATE paiement SET statut = 'réussi' WHERE idcmd = ?");
    $stmt->bind_param("i", $idcmd);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE commande SET statut = 'validée', code_retrait = ?, statut_retrait = 'en attente' WHERE idcmd = ?");
    $stmt->bind_param("si", $code, $idcmd);
    $stmt->execute();

    // Option : Envoyer par mail ou SMS ici

    header("Location: commande.php");
    exit;
}
?>
