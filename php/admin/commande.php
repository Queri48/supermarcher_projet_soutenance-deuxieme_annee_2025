<?php
session_start();
require '../database.php';
require '../helpers.php';
include 'header.php';
$title = "Gestion des commandes";

$sql = "SELECT c.*, p.statut AS paiement_statut, p.methode_paiement, p.date_paiement
        FROM commande c
        LEFT JOIN paiement p ON c.idcmd = p.idcmd
        ORDER BY c.date_commande DESC";
$result = $conn->query($sql);
?>

<div class="container py-5">
    <h2 class="mb-4"><?= $title ?></h2>

    <div class="mb-3">
        <input type="text" id="rechercheCode" class="form-control" placeholder="Rechercher par code retrait...">
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Client</th>
                <th>Montant</th>
                <th>Numéro</th>
                <th>Adresse</th>
                <th>Paiement</th>
                <th>Statut commande</th>
                <th>Code retrait</th>
                <th>Récupération</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cmd = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $cmd['idcmd'] ?></td>
                    <td><?= $cmd['date_commande'] ?></td>
                    <td><?= $cmd['id'] ?></td>
                    <td><?= number_format($cmd['montant_total'], 0, ',', ' ') ?> FCFA</td>
                    <td>0<?= e($cmd['numero']) ?></td>
                    <td><?= e($cmd['adresse_livraison']) ?></td>

                    <!-- Statut Paiement -->
                    <td>
                        <?php if ($cmd['paiement_statut'] === 'réussi'): ?>
                            <span class="badge bg-success">Payé</span>
                        <?php elseif ($cmd['paiement_statut'] === 'en attente'): ?>
                            <span class="badge bg-warning text-dark">En attente</span>
                        <?php elseif ($cmd['paiement_statut'] === 'échoué'): ?>
                            <span class="badge bg-danger">Échoué</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">N/A</span>
                        <?php endif; ?>
                    </td>

                    <!-- Statut Commande -->
                    <td>
                        <?php if ($cmd['statut'] === 'annulée'): ?>
                            <span class="badge bg-danger">Annulée</span>
                        <?php elseif (!empty($cmd['statut'])): ?>
                            <span class="badge bg-info text-white"><?= ucfirst($cmd['statut']) ?></span>
                        <?php else: ?>
                            <span class="badge bg-secondary">-</span>
                        <?php endif; ?>
                    </td>

                    <!-- Code Retrait -->
                    <td>
                        <?= $cmd['code_retrait'] ? '<strong>' . htmlspecialchars($cmd['code_retrait']) . '</strong>' : '<em>Annulé</em>' ?>
                    </td>

                    <!-- Récupération -->
                    <td>
                        <?php if ($cmd['statut_retrait'] === 'récupérée'): ?>
                            <span class="badge bg-success">Terminé</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">En attente</span>
                        <?php endif; ?>
                    </td>

                    <!-- Actions -->
                    <td>
                        <!-- Valider Paiement -->
                        <?php if ($cmd['paiement_statut'] === 'en attente' && $cmd['statut'] !== 'annulée'): ?>
                            <form method="POST" action="paiement.php" class="d-inline">
                                <input type="hidden" name="idcmd" value="<?= $cmd['idcmd'] ?>">
                                <button class="btn btn-sm btn-success mb-1" onclick="return confirm('Confirmer le paiement ?')">
                                    <i class="fas fa-check"></i> Paiement
                                </button>
                            </form>
                        <?php endif; ?>

                        <!-- Valider Retrait sans champ -->
                        <?php if (
                            $cmd['paiement_statut'] === 'réussi' &&
                            $cmd['statut'] !== 'annulée' &&
                            $cmd['statut_retrait'] === 'en attente' &&
                            $cmd['code_retrait']
                        ): ?>
                            <form method="POST" action="retrait.php" class="d-inline">
                                <input type="hidden" name="idcmd" value="<?= $cmd['idcmd'] ?>">
                                <input type="hidden" name="code" value="<?= htmlspecialchars($cmd['code_retrait']) ?>">
                                <button class="btn btn-sm btn-primary mb-0" onclick="return confirm('Valider la récupération de la commande ?')">
                                    <i class="fas fa-box"></i> Retrait
                                </button>
                            </form>
                        <?php endif; ?>

                        <!-- Annuler -->
                        <?php if ($cmd['statut'] !== 'annulée' && $cmd['paiement_statut'] === 'en attente'): ?>
                            <a href="annuler.php?idcmd=<?= $cmd['idcmd'] ?>" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?');">
                                <i class="fas fa-ban"></i> Annuler
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('rechercheCode').addEventListener('input', function() {
        let filter = this.value.trim().toLowerCase();
        let lignes = document.querySelectorAll('table tbody tr');

        lignes.forEach(function(row) {
            let code = row.querySelector('td:nth-child(9)').innerText.toLowerCase();
            if (filter === '' || code.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>