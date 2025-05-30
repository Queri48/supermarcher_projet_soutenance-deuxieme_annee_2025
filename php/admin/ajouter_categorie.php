<?php
session_start();
$title = "Ajouter une nouvelle catégorie";
require '../database.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $resume = htmlspecialchars($_POST['resume']);
    $description = htmlspecialchars($_POST['description']);

    // Gérer l'image
    $imageData = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    if ($imageData !== null) {
        $stmt = $conn->prepare("INSERT INTO categorie (titre, resume, description, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $titre, $resume, $description, $imageData);

        if ($stmt->execute()) {
            $message = "Catégorie ajouté avec succès.";
        } else {
            $message = "Erreur : " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Veuillez sélectionner une image valide.";
    }
}
include '../header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 bg-white p-5 rounded shadow-sm">
            <h2 class="mb-4 text-center"><?= $title ?></h2>

            <?php if (!empty($message)): ?>
                <div class="alert alert-info"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>

                <div class="mb-3">
                    <label for="resume" class="form-label">Résumé</label>
                    <input type="text" class="form-control" id="resume" name="resume" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Ajouter Catégorie</button>
            </form>
        </div>
    </div>
</div>
