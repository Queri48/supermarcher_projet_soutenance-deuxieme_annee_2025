<?php
session_start();
require 'database.php';
require 'helpers.php';
include 'header.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération des commandes du client
$sql = "SELECT c.*, p.statut AS paiement_statut, p.methode_paiement, p.date_paiement
        FROM commande c
        LEFT JOIN paiement p ON c.idcmd = p.idcmd
        WHERE c.id = ?
        ORDER BY c.date_commande DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-5">
    <h2 class="mb-4 text-superu">Mon Historique de Commandes</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Commande</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Paiement</th>
                        <th>Statut</th>
                        <th>Code Retrait</th>
                        <th>Récupération</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($cmd = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $cmd['idcmd'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($cmd['date_commande'])) ?></td>
                            <td><?= number_format($cmd['montant_total'], 0, ',', ' ') ?> FCFA</td>
                            <td><?= strtoupper($cmd['methode_paiement'] ?? '-') ?></td>
                            <td>
                                <?php if ($cmd['paiement_statut'] === 'réussi'): ?>
                                    <span class="badge bg-success">Payé</span>
                                <?php elseif ($cmd['paiement_statut'] === 'en attente'): ?>
                                    <span class="badge bg-warning text-dark">En attente</span>
                                <?php elseif ($cmd['paiement_statut'] === 'échoué'): ?>
                                    <span class="badge bg-danger">Échoué</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($cmd['statut'] === 'annulée'): ?>
                                    <span class="badge bg-danger">Annulée</span>
                                <?php elseif (!empty($cmd['statut'])): ?>
                                    <span class="badge bg-info"><?= ucfirst($cmd['statut']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $cmd['code_retrait'] ? '<strong>' . htmlspecialchars($cmd['code_retrait']) . '</strong>' : '<em>Annulé</em>' ?>
                            </td>
                            <td>
                                <?php if ($cmd['statut_retrait'] === 'récupérée'): ?>
                                    <span class="badge bg-success">Récupérée</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">En attente</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="alert alert-info">Vous n'avez passé aucune commande pour l'instant.</p>
    <?php endif; ?>
</div>
