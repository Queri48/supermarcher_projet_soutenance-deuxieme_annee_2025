<?php
session_start();
$title = "Modifier un employer";
require '../database.php';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: employer.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM utilisateur WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Employer introuvable.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $tel = htmlspecialchars(trim($_POST['tel']));
    $adresse = htmlspecialchars(trim($_POST['adresse']));
    $password = $_POST['password'];
    $valide = 1;
    $datetime = date("Y-m-d H:i:s");

    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE utilisateur SET nom=?, prenom=?, email=?, tel=?, adresse=?, password=?, valide=?, role=?, datetime=? WHERE id=?");
        $stmt->bind_param("ssssssissi", $nom, $prenom, $email, $tel, $adresse, $password_hash, $valide, $role, $datetime, $id);
    } else {
        $stmt = $conn->prepare("UPDATE utilisateur SET nom=?, prenom=?, email=?, tel=?, adresse=?, valide=?, role=?, datetime=? WHERE id=?");
        $stmt->bind_param("ssssssisi", $nom, $prenom, $email, $tel, $adresse, $valide, $role, $datetime, $id);
    }

    $stmt->execute();
    header("Location: employer.php");
    exit;
}
include '../header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 bg-white p-4 rounded shadow-sm">
            <h2 class="mb-4 text-center"><?= $title ?></h2>
            <form method="POST">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="tel" class="form-control" value="<?= htmlspecialchars($user['tel']) ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($user['adresse']) ?>" required>
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

                <div class="mb-3">
                    <label class="form-label">Date/Heure d’inscription</label>
                    <input type="datetime-local" name="datetime" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($user['datetime'])) ?>" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success">Mettre à jour</button>
                    <a href="employer.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
