<?php
session_start();
$title = "Gérer les Promotions";
require '../database.php';
require '../helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titre = e($_POST['titre']);
  $description = e($_POST['description']);
  $pourcentage = e((int)$_POST['pourcentage']);
  $date_debut = e($_POST['date_debut']);
  $date_fin = e($_POST['date_fin']);
  $articles = $_POST['articles'] ?? [];

  if ($titre && $pourcentage > 0 && !empty($articles)) {
    $conn->begin_transaction();

    try {
      $stmt = $conn->prepare("INSERT INTO promotion (titre, description, datetime) VALUES (?, ?, NOW())");
      $stmt->bind_param("ss", $titre, $description);
      $stmt->execute();
      $idpromo = $stmt->insert_id;

      $stmtAppliquer = $conn->prepare("INSERT INTO appliquer (idpromo, idart, date_debut, date_fin, pourcentage) VALUES (?, ?, ?, ?, ?)");
      foreach ($articles as $idart) {
        $stmtAppliquer->bind_param("iissi", $idpromo, $idart, $date_debut, $date_fin, $pourcentage);
        $stmtAppliquer->execute();
      }

      $conn->commit();
      $success = "Promotion enregistrée avec succès.";
    } catch (Exception $e) {
      $conn->rollback();
      $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
  } else {
    $error = "Tous les champs sont obligatoires.";
  }
}

$categories = $conn->query("SELECT * FROM categorie ORDER BY titre")->fetch_all(MYSQLI_ASSOC);
$articles_par_categorie = [];

foreach ($categories as $cat) {
  $stmt = $conn->prepare("SELECT idart, titre FROM article WHERE idcat = ?");
  $stmt->bind_param("i", $cat['idcat']);
  $stmt->execute();
  $articles_par_categorie[$cat['idcat']] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

include 'header.php';
?>

<div class="container py-4">
  <h2>Créer une promotion</h2>

  <?php if (isset($success)) : ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif (isset($error)) : ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Titre de la promotion</label>
        <input type="text" name="titre" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Pourcentage (%)</label>
        <input type="number" name="pourcentage" class="form-control" min="1" max="100" required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3"></textarea>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Date de début</label>
        <input type="date" name="date_debut" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Date de fin</label>
        <input type="date" name="date_fin" class="form-control" required>
      </div>
    </div>

    <hr>
    <div class="mb-3">

      <h5>Sélectionner les produits</h5>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="selectAllGlobal">
        <label class="form-check-label fw-bold" for="selectAllGlobal">Tout sélectionner</label>
      </div>

      <div class="row">
        <?php foreach ($categories as $cat): ?>
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm p-3">
              <div class="d-flex justify-content-between align-items-center">
                <div class="form-check mb-0">
                  <input class="form-check-input select-cat" type="checkbox"
                    id="cat-<?= $cat['idcat'] ?>"
                    data-idcat="<?= $cat['idcat'] ?>"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapse-cat-<?= $cat['idcat'] ?>">
                  <label class="form-check-label fw-bold" for="cat-<?= $cat['idcat'] ?>">
                    <?= e($cat['titre']) ?>
                  </label>
                </div>
                <div>
                  <input class="form-check-input select-cat-items" type="checkbox"
                    id="select-all-<?= $cat['idcat'] ?>"
                    data-idcat="<?= $cat['idcat'] ?>">
                  <label class="form-check-label small" for="select-all-<?= $cat['idcat'] ?>">Sélectionner tout</label>
                </div>
              </div>
              <div id="collapse-cat-<?= $cat['idcat'] ?>" class="collapse mt-2">
                <?php foreach ($articles_par_categorie[$cat['idcat']] as $article): ?>
                  <div class="form-check ps-3">
                    <input class="form-check-input checkbox-article"
                      type="checkbox" name="articles[]" value="<?= $article['idart'] ?>"
                      data-idcat="<?= $cat['idcat'] ?>"
                      id="art-<?= $article['idart'] ?>">
                    <label class="form-check-label small" for="art-<?= $article['idart'] ?>">
                      <?= e($article['titre']) ?>
                    </label>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-success">Valider la promotion</button>
    </div>
  </form>
</div>