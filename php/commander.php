<?php
session_start();
require 'database.php';
require_once 'helpers.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Vérifier si le panier n'est pas vide
$panier = $_SESSION['panier'] ?? [];
if (empty($panier)) {
    echo '<div class="alert alert-warning text-center mt-5">Votre panier est vide.</div>';
    exit;
}

$title = "Finaliser la commande";
$total = 0;
 
include 'header.php'; 
?>

<div class="container py-5">
    <h2 class="mb-4">Récapitulatif de votre commande</h2>

    <div class="table-responsive mb-4">
        <table class="table table-bordered">
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
                    $total += $totalProduit;
                ?>
                    <tr>
                        <td><?= $titre ?></td>
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
            <input type="text" name="adresse" id="adresse" class="form-control" required placeholder="Ex : Quartier, ville...">
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
            <label for="numero" class="form-label">Numéro de téléphone (Mobile Money)</label>
            <input type="tel" name="numero" id="numero" class="form-control" required placeholder="Ex : 01xxxxxxxx / 05xxxxxxxx">
        </div>

        <input type="hidden" name="montant" value="<?= $total ?>">

        <div class="text-center">
            <button type="submit" class="btn btn-success w-50">
                <i class="fas fa-check-circle me-1"></i> Valider la commande
            </button>
        </div>
    </form>
</div>

