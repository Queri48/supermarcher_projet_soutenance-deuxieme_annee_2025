<?php
session_start();
$title = "Modifier Produit";
require '../database.php';
require '../helpers.php';

if (!isset($_GET['idart']) || !is_numeric($_GET['idart'])) {
    die("ID de produit invalide.");
}

$idart = (int)$_GET['idart'];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = e(trim($_POST['titre']));
    $prix = e((float)$_POST['prix']);
    $resume = e(trim($_POST['resume']));
    $description = e(trim($_POST['description']));
    $quantiteAjoutee = e((int)$_POST['quantite_stock']);
    $idcat = e((int)$_POST['idcat']);

    if (empty($titre) || $prix <= 0 || $idcat <= 0) {
        $erreur = "Veuillez remplir tous les champs obligatoires correctement.";
    } else {
        // Récupérer l'ancienne quantité
        $stmt = $conn->prepare("SELECT quantite_stock FROM article WHERE idart = ?");
        $stmt->bind_param("i", $idart);
        $stmt->execute();
        $res = $stmt->get_result();
        $ancien = $res->fetch_assoc();
        $nouvelleQuantite = $ancien['quantite_stock'] + $quantiteAjoutee;

        // Image modifiée ou non ?
        if (!empty($_FILES['image']['tmp_name'])) {
            $imageData = e(file_get_contents($_FILES['image']['tmp_name']));
            $sql = "UPDATE article SET titre=?, prix=?, resume=?, description=?, quantite_stock=?, idcat=?, image=? WHERE idart=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdssdisi", $titre, $prix, $resume, $description, $nouvelleQuantite, $idcat, $imageData, $idart);
        } else {
            $sql = "UPDATE article SET titre=?, prix=?, resume=?, description=?, quantite_stock=?, idcat=? WHERE idart=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdssiii", $titre, $prix, $resume, $description, $nouvelleQuantite, $idcat, $idart);
        }

        
        if ($stmt->execute()) {
            header("Location: produit.php?success=1");
            exit;
        } else {
            $erreur = "Erreur lors de la mise à jour.";
        }
    }
}

// Récupérer les infos actuelles
$stmt = $conn->prepare("SELECT * FROM article WHERE idart = ?");
$stmt->bind_param("i", $idart);
$stmt->execute();
$res = $stmt->get_result();
$produit = $res->fetch_assoc();

if (!$produit) {
    die("Produit introuvable.");
}

// Récupérer les catégories pour le dropdown
$categories = $conn->query("SELECT idcat, titre FROM categorie")->fetch_all(MYSQLI_ASSOC);

include 'header.php';
?>

<div class="container py-5">
    <h2>Modifier le Produit</h2>

    <?php if (isset($erreur)) : ?>
        <div class="alert alert-danger"><?= e($erreur) ?></div>
    <?php elseif (isset($success)) : ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" id="titre" class="form-control" value="<?= e($produit['titre']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="resume" class="form-label">Resume</label>
            <input type="text" name="resume" id="resume" class="form-control" value="<?= e($produit['resume']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4"><?= e($produit['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="prix" class="form-label">Prix</label>
            <input type="number" step="0.01" name="prix" id="prix" class="form-control" value="<?= e($produit['prix']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="quantite_stock" class="form-label">Ajouter une quantité au stock (actuellement : <?= $produit['quantite_stock'] ?>)</label>
            <input type="number" name="quantite_stock" id="quantite_stock" class="form-control" min="0" value="0">
        </div>

        <div class="mb-3">
            <label for="idcat" class="form-label">Catégorie</label>
            <select name="idcat" id="idcat" class="form-select" required>
                <?php foreach ($categories as $cat) : ?>
                    <option value="<?= $cat['idcat'] ?>" <?= ($produit['idcat'] == $cat['idcat']) ? 'selected' : '' ?>>
                        <?= e($cat['titre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image (laisser vide pour conserver l'image actuelle)</label><br>
            <?php if (!empty($produit['image'])) : ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($produit['image']) ?>" alt="Image actuelle" style="width: 100px; height: auto; margin-bottom: 10px;">
            <?php endif; ?>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>
