<?php
session_start();
$title = "Modifier un administrateur";
require '../database.php';
require '../helpers.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) {
    header("Location: administrateur.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM utilisateur WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Administrateur introuvable.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = e(trim($_POST['nom']));
    $prenom = e(trim($_POST['prenom']));
    $email = e(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $tel = e(trim($_POST['tel']));
    $adresse = e(trim($_POST['adresse']));
    $password = e($_POST['password']);
    $valide = (int)($_POST['valide']);
    $role = (int)($_POST['role']);

    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE utilisateur SET nom=?, prenom=?, email=?, tel=?, adresse=?, password=?, valide=?, role=? WHERE id=?");
        $stmt->bind_param("ssssssisi", $nom, $prenom, $email, $tel, $adresse, $password_hash, $valide, $role, $id);
    } else {
        $stmt = $conn->prepare("UPDATE utilisateur SET nom=?, prenom=?, email=?, tel=?, adresse=?, valide=?, role=? WHERE id=?");
        $stmt->bind_param("ssssssii", $nom, $prenom, $email, $tel, $adresse, $valide, $role, $id);
    }

    $stmt->execute();
    header("Location: administrateur.php");
    exit;
}
include 'header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 bg-white p-4 rounded shadow-sm">
            <h2 class="mb-4 text-center"><?= $title ?></h2>
            <?php if (!empty($erreur)) : ?>
                <div class="alert alert-danger"><?= e($erreur) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" value="<?= e($user['nom']) ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="prenom" class="form-control" value="<?= e($user['prenom']) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="tel" class="form-control" value="<?= e($user['tel']) ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="adresse" class="form-control" value="<?= e($user['adresse']) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Statut (valide)</label>
                        <select name="valide" class="form-select">
                            <option value="1" <?= $user['valide'] ? 'selected' : '' ?>>Oui</option>
                            <option value="0" <?= !$user['valide'] ? 'selected' : '' ?>>Non</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Rôle</label>
                        <select name="role" class="form-select">
                            <option value="0" <?= $user['role'] == 0 ? 'selected' : '' ?>>Admin</option>
                            <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>Employé</option>
                            <option value="2" <?= $user['role'] == 2 ? 'selected' : '' ?>>Client</option>
                        </select>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success">Mettre à jour</button>
                    <a href="administrateur.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>