<?php
session_start();
$title = "Ajouter un Employer";
require '../database.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $tel = htmlspecialchars(trim($_POST['tel']));
    $adresse = htmlspecialchars(trim($_POST['adresse']));
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $valide = 1;
    $role = 1;
    $datetime = date("Y-m-d H:i:s");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format d'email invalide.";
    } elseif (!preg_match('/^\d{10}$/', $tel)) {
        $message = "Le numéro de téléphone doit contenir 10 chiffres.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        $message = "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
    } elseif ($password !== $cpassword) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        $stmt_check_email = $conn->prepare("SELECT * FROM utilisateur WHERE email = ? AND valide = 1");
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $result = $stmt_check_email->get_result();

        if ($result->num_rows > 0) {
            $message = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
        } else {
            $stmt_check_email->close();

            // Hachage du mot de passe sécurisé
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO utilisateur (nom, prenom, email, tel, adresse, password, valide, role, datetime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $nom, $prenom, $email, $tel, $adresse, $hashed_password, $valide, $role, $datetime);

            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                $stmt->close();

                $stmt_get_user = $conn->prepare("SELECT id, role FROM utilisateur WHERE id = ?");
                $stmt_get_user->bind_param("i", $user_id);
                $stmt_get_user->execute();
                $result_user = $stmt_get_user->get_result();
                $user = $result_user->fetch_assoc();
                $stmt_get_user->close();

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];

                header("Location: employer.php");
                exit;
            } else {
                $message = "Erreur lors de l'inscription : " . $stmt->error;
            }
        }
    }
}
include '../header.php';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white p-4 rounded shadow">
            <h2 class="text-center mb-4"><?= $title ?></h2>

            <?php if (!empty($message)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" name="nom" id="nom" required>
                </div>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" name="prenom" id="prenom" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="tel" class="form-label">Téléphone</label>
                    <input type="text" class="form-control" name="tel" id="tel" required>
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <textarea class="form-control" name="adresse" id="adresse" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <div class="mb-3">
                    <label for="cpassword" class="form-label"> Confirmer Mot de passe</label>
                    <input type="password" class="form-control" name="cpassword" id="cpassword" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
            </form>
        </div>
    </div>
</div>