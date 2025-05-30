<?php
session_start();
$title = "Ajouter un nouveau produit";
require '../database.php';
$message = "";

// Récupération des catégories pour la liste déroulante
$categories = $conn->query("SELECT idcat, titre FROM categorie")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $resume = htmlspecialchars($_POST['resume']);
    $description = htmlspecialchars($_POST['description']);
    $prix = floatval($_POST['prix']);
    $quantite_stock = intval($_POST['quantite_stock']);
    $idcat = intval($_POST['idcat']);

    // Gérer l'image
    $imageData = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    if ($imageData !== null) {
        $stmt = $conn->prepare("INSERT INTO article (titre, resume, description, prix, quantite_stock, image, idcat) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdisi", $titre, $resume, $description, $prix, $quantite_stock, $imageData, $idcat);

        if ($stmt->execute()) {
            $message = "Produit ajouté avec succès.";
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
                    <label for="prix" class="form-label">Prix</label>
                    <input type="number" class="form-control" step="0.01" name="prix" id="prix" required>
                </div>

                <div class="mb-3">
                    <label for="quantite_stock" class="form-label">Quantité en stock</label>
                    <input type="number" class="form-control" name="quantite_stock" id="quantite_stock" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>

                <div class="mb-3">
                    <label for="idcat" class="form-label">Catégorie</label>
                    <select name="idcat" id="idcat" class="form-select" required>
                        <option value="">Choisir une catégorie</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['idcat'] ?>"><?= htmlspecialchars($cat['titre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Ajouter produit</button>
            </form>
        </div>
    </div>
</div>
