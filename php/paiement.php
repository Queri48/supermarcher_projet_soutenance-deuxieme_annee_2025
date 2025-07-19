<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['idcmd']) || !is_numeric($_GET['idcmd'])) {
    die("Commande invalide.");
}

$idcom = (int)$_GET['idcmd'];
$user_id = $_SESSION['user_id'];

// 1. Vérifier que la commande appartient à l'utilisateur
$stmt = $conn->prepare("SELECT * FROM commande WHERE idcmd = ? AND id = ?");
$stmt->bind_param("ii", $idcom, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$commande = $result->fetch_assoc();

if (!$commande) {
    die("Commande introuvable.");
}

// 2. Récupérer le paiement
$stmt = $conn->prepare("SELECT * FROM paiement WHERE idcmd = ?");
$stmt->bind_param("i", $idcom);
$stmt->execute();
$paiement = $stmt->get_result()->fetch_assoc();

if (!$paiement || empty($paiement['statut'])) {
    die("Erreur : Paiement introuvable ou statut vide.");
}

$statut = $paiement['statut'];

// 3. Générer le code de retrait si le paiement est validé et pas encore de code
if ($statut === 'réussi' && empty($commande['code_retrait'])) {
    $code_retrait = str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
    $stmt = $conn->prepare("UPDATE commande SET code_retrait = ?, statut_retrait = 'en attente' WHERE idcmd = ?");
    $stmt->bind_param("si", $code_retrait, $idcom);
    $stmt->execute();
    $commande['code_retrait'] = $code_retrait;
    $commande['statut_retrait'] = 'en attente';
}

include 'header.php';
?>

<?php if ($statut === 'en attente'): ?>
    <div class="container py-5 text-center">
        <h2 class="text-warning"><i class="fas fa-clock"></i> Paiement en attente ...</h2><br>
        <p class="text-superu">Nous avons bien reçu votre commande. <br> Veuillez confirmer le paiement sur votre téléphone.</p>
        <p class="text-superu">Numéro : <strong>0<?= htmlspecialchars($paiement['numero']) ?></strong></p>
    </div>

<?php elseif ($statut === 'réussi'): ?>
    <div class="container py-5">
        <h2 class="text-success"><i class="fas fa-check-circle"></i> Paiement confirmé</h2>
        <ul class="list-group mt-4">
            <li class="list-group-item">Commande n° <?= $commande['idcmd'] ?></li>
            <li class="list-group-item">Montant : <?= number_format($commande['montant_total'], 0, ',', ' ') ?> FCFA</li>
            <li class="list-group-item">Méthode : <?= strtoupper($paiement['methode_paiement']) ?></li>
            <li class="list-group-item">Téléphone : 0<?= htmlspecialchars($paiement['numero']) ?></li>
            <li class="list-group-item">Statut : <?= ucfirst($paiement['statut']) ?></li>
            <li class="list-group-item bg-light">
                <strong>Code de retrait :</strong> <span class="text-danger fs-4"><?= $commande['code_retrait'] ?></span><br>
                <small class="text-muted">Présentez ce code pour récupérer votre colis</small>
            </li>
        </ul>
    </div>

<?php elseif ($statut === 'échoué'): ?>
    <div class="container py-5 text-center">
        <h2 class="text-danger"><i class="fas fa-times-circle"></i> Paiement échoué</h2>
        <p>Le paiement a échoué. Veuillez réessayer ou contacter le support.</p>
        <a href="contact.php" class="btn btn-outline-danger">Contacter le support</a>
    </div>

<?php else: ?>
    <div class="container py-5 text-center">
        <h2 class="text-danger">Erreur</h2>
        <p>Statut de paiement inconnu.</p>
    </div>
<?php endif; ?>
