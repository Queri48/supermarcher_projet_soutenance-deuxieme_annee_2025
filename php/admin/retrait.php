<?php
require '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idcmd'], $_POST['code'])) {
    $idcmd = (int)$_POST['idcmd'];
    $code_saisi = trim($_POST['code']);

    // Rechercher la commande et vérifier le code et le statut
    $stmt = $conn->prepare("SELECT code_retrait, statut_retrait FROM commande WHERE idcmd = ?");
    $stmt->bind_param("i", $idcmd);
    $stmt->execute();
    $result = $stmt->get_result();
    $commande = $result->fetch_assoc();

    if (
        $commande &&
        $commande['statut_retrait'] === 'en attente' &&
        $commande['code_retrait'] === $code_saisi
    ) {
        // Mise à jour du statut de retrait
        $stmt = $conn->prepare("UPDATE commande SET statut_retrait = 'récupérée' WHERE idcmd = ?");
        $stmt->bind_param("i", $idcmd);
        $stmt->execute();
    }

    // Redirection vers la liste des commandes
    header("Location: commande.php");
    exit;
}
?>
