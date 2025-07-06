<?php
session_start();
$title = "Modifier une Promotion";
require '../database.php';
require_once '../helpers.php';

if (!isset($_GET['idpromo']) || !is_numeric($_GET['idpromo'])) {
    die("Promotion invalide.");
}

$idpromo = (int)$_GET['idpromo'];

// Charger les données actuelles de la promotion
$stmt = $conn->prepare("SELECT * FROM promotion WHERE idpromo = ?");
$stmt->bind_param("i", $idpromo);
$stmt->execute();
$promo = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$promo) {
    die("Promotion introuvable.");
}

// Charger les produits déjà associés
$produitsAssocies = [];
$stmt = $conn->prepare("SELECT idart FROM appliquer WHERE idpromo = ?");
$stmt->bind_param("i", $idpromo);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $produitsAssocies[] = $row['idart'];
}
$stmt->close();

// Soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = e(trim($_POST['titre']));
    $description = e(trim($_POST['description']));
    $date_debut = e($_POST['date_debut']);
    $date_fin = e($_POST['date_fin']);
    $pourcentage = e((int)$_POST['pourcentage']);
    $produits = isset($_POST['produits']) ? $_POST['produits'] : [];

    if (empty($titre) || empty($description) || empty($date_debut) || empty($date_fin) || empty($produits)) {
        $erreur = "Tous les champs sont requis.";
    } else {
        // Mettre à jour la table promotion
        $stmt = $conn->prepare("UPDATE promotion SET titre = ?, description = ?, datetime = NOW() WHERE idpromo = ?");
        $stmt->bind_param("ssi", $titre, $description, $idpromo);
        $stmt->execute();
        $stmt->close();

        // Supprimer les anciennes associations
        $stmt = $conn->prepare("DELETE FROM appliquer WHERE idpromo = ?");
        $stmt->bind_param("i", $idpromo);
        $stmt->execute();
        $stmt->close();

        // Réinsérer les nouvelles associations
        $stmt = $conn->prepare("INSERT INTO appliquer (idpromo, idart, date_debut, date_fin, pourcentage) VALUES (?, ?, ?, ?, ?)");
        foreach ($produits as $idart) {
            $stmt->bind_param("iissi", $idpromo, $idart, $date_debut, $date_fin, $pourcentage);
            $stmt->execute();
        }
        $stmt->close();

        $_SESSION['success'] = "Promotion modifiée avec succès.";
        header("Location: promotion.php");
        exit;
    }
}

// Charger toutes les catégories et produits
$categories = [];
$stmt = $conn->query("SELECT c.idcat, c.titre AS cat_titre, a.idart, a.titre AS art_titre
                      FROM categorie c
                      JOIN article a ON a.idcat = c.idcat
                      ORDER BY c.titre, a.titre");

while ($row = $stmt->fetch_assoc()) {
    $categories[$row['idcat']]['titre'] = $row['cat_titre'];
    $categories[$row['idcat']]['produits'][] = ['idart' => $row['idart'], 'titre' => $row['art_titre']];
}
$stmt->close();

include 'header.php';
?>

<div class="container py-5">
    <h2 class="mb-4">Modifier la Promotion</h2>

    <?php if (isset($erreur)): ?>
        <div class="alert alert-danger"><?= $erreur ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Titre de la promotion</label>
            <input type="text" name="titre" class="form-control" value="<?= e($promo['titre']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required><?= e($promo['description']) ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Date de début</label>
                <input type="date" name="date_debut" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Date de fin</label>
                <input type="date" name="date_fin" class="form-control" value="<?= date('Y-m-d')?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Pourcentage</label>
                <input type="number" name="pourcentage" class="form-control" value="<?= $promo['pourcentage'] ?? 10 ?>" required min="1" max="90">
            </div>
        </div>

        <h5 class="mt-4">Sélectionner les produits à appliquer</h5>
        <?php foreach (array_chunk($categories, 3, true) as $chunk): ?>
            <div class="row">
                <?php foreach ($chunk as $idcat => $cat): ?>
                    <div class="col-md-4 mb-3 border p-2 rounded shadow-sm">
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input select-cat" id="cat-<?= $idcat ?>">
                            <label class="form-check-label fw-bold" for="cat-<?= $idcat ?>"><?= e($cat['titre']) ?> (Sélectionner tout)</label>
                        </div>

                        <div class="ps-3">
                            <?php foreach ($cat['produits'] as $prod): ?>
                                <div class="form-check">
                                    <input class="form-check-input checkbox-produit cat-<?= $idcat ?>"
                                        type="checkbox" name="produits[]"
                                        value="<?= $prod['idart'] ?>"
                                        id="prod-<?= $prod['idart'] ?>"
                                        <?= in_array($prod['idart'], $produitsAssocies) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="prod-<?= $prod['idart'] ?>">
                                        <?= e($prod['titre']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success px-5">
                <i class="fas fa-save me-1"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>