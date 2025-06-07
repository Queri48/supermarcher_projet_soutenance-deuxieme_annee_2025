<?php
session_start();
require 'database.php';
require_once 'helpers.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$title = "Finaliser la commande";

$sql = "SELECT p.idpan, p.quantite, a.titre, a.prix, a.image 
        FROM panier p
        JOIN article a ON p.idart = a.idart
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$panier = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $panier[] = $row;
    $total += $row['prix'] * $row['quantite'];
}

if (empty($panier)) {
    echo '<div class="alert alert-warning text-center mt-5">Votre panier est vide.</div>';
    exit;
}

include 'header.php';
?>

<div class="container py-5">
    <h2 class="mb-4">Récapitulatif de votre commande</h2>

    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($panier as $produit):
                    $titre = e($produit['titre']);
                    $quantite = (int)$produit['quantite'];
                    $prix = (float)$produit['prix'];
                    $totalProduit = $quantite * $prix;
                ?>
                    <tr>
                        <td>
                            <?php if (!empty($produit['image'])):
                                $image = base64_encode($produit['image']);
                            ?>
                                <img src="data:image/jpeg;base64,<?= $image ?>" alt="<?= $titre ?>" style="width: 50px; height: auto; margin-right: 10px;">
                            <?php endif; ?>
                            <?= $titre ?>
                        </td>
                        <td><?= $quantite ?></td>
                        <td><?= number_format($prix, 0, ',', ' ') ?> FCFA</td>
                        <td><?= number_format($totalProduit, 0, ',', ' ') ?> FCFA</td>
                    </tr>
                <?php endforeach; ?>
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Total à payer :</td>
                    <td><?= number_format($total, 0, ',', ' ') ?> FCFA</td>
                </tr>
            </tbody>
        </table>
    </div>

    <form method="POST" action="traitement_commande.php">
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse de livraison</label>
            <div class="input-group">
                <input type="text" name="adresse" id="adresse" class="form-control" required placeholder="Déplacez le marqueur ou utilisez votre position actuelle">
                <button type="button" id="get-location" class="btn btn-outline-primary">Mon emplacement</button>
            </div>
            <div id="map" style="height: 300px; margin-top: 10px;"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Méthode de paiement</label>
            <select name="mode_paiement" class="form-select" required>
                <option value="">-- Choisir une méthode --</option>
                <option value="mtn">MTN Mobile Money</option>
                <option value="moov">Moov Money</option>
                <option value="celtiis">Celtiis Pay</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="numero" class="form-label">Numéro Mobile Money</label>
            <input type="tel" name="numero" id="numero" class="form-control" required
                   pattern="01[0-9]{8}" maxlength="10" minlength="10"
                   placeholder="Ex: 01xxxxxxxx">
        </div>

        <input type="hidden" name="montant" value="<?= $total ?>">

        <div class="text-center">
            <button type="submit" class="btn btn-success w-50">
                <i class="fas fa-check-circle me-1"></i> Valider la commande
            </button>
        </div>
    </form>
</div>
