<?php
session_start();
$title = "Modifier Catégorie";
require '../database.php';
require '../helpers.php';

if (!isset($_GET['idcat']) || !is_numeric($_GET['idcat'])) {
    die("ID de catégorie invalide.");
}

$idcat = (int)$_GET['idcat'];
$erreur = "";

// Récupérer les infos actuelles
$stmt = $conn->prepare("SELECT * FROM categorie WHERE idcat = ?");
$stmt->bind_param("i", $idcat);
$stmt->execute();
$result = $stmt->get_result();
$categorie = $result->fetch_assoc();

if (!$categorie) {
    die("Catégorie non trouvée.");
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = e(trim($_POST['titre']));
    $resume = e(trim($_POST['resume']));
    $description = e(trim($_POST['description']));

    if (empty($titre)) {
        $erreur = "Le titre est requis.";
    } else {
        if (!empty($_FILES['image']['tmp_name'])) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            $sql = "UPDATE categorie SET titre = ?, resume = ?, description = ?, image = ? WHERE idcat = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $titre, $resume, $description, $imageData, $idcat);
        } else {
            $sql = "UPDATE categorie SET titre = ?, resume = ?, description = ? WHERE idcat = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $titre, $resume, $description, $idcat);
        }

        if ($stmt->execute()) {
            header("Location: categorie.php?success=1");
            exit;
        } else {
            $erreur = "Erreur lors de la mise à jour.";
        }
    }
}

include 'header.php';
?>

<div class="container py-5">
    <h2>Modifier la Catégorie</h2>

    <?php if (!empty($erreur)) : ?>
        <div class="alert alert-danger"><?= e($erreur) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" id="titre" class="form-control" value="<?= e($categorie['titre']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="resume" class="form-label">Résumé</label>
            <input type="text" name="resume" id="resume" class="form-control" value="<?= e($categorie['resume']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" required><?= e($categorie['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Image actuelle</label><br>
            <?php if (!empty($categorie['image'])): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($categorie['image']) ?>" alt="Image Catégorie" class="img-thumbnail" style="max-width: 200px;">
            <?php else: ?>
                <p>Aucune image enregistrée</p>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Changer l'image (optionnel)</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>
