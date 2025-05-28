<?php
session_start();
$title = "Connexion";
require 'database.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse email invalide.";
    } elseif (empty($password)) {
        $message = "Le mot de passe est requis.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = ? AND valide = 1 LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];

                if ($user['role'] == 0) {
                    header("Location: admin/tableau_bord.php");
                } elseif ($user['role'] == 1) {
                    header("Location: employer/tableau_bord.php");
                } else {
                    // Client : on redirige vers la dernière page visitée si elle existe
                    $redirect = $_SESSION['redirect_after_login'] ?? 'index.php';
                    unset($_SESSION['redirect_after_login']);
                    header("Location: $redirect");
                }
                exit;
            } else {
                $message = "Mot de passe incorrect.";
            }
        } else {
            $message = "Aucun compte trouvé avec cette adresse email.";
        }

        $stmt->close();
    }
}
include 'header.php';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white p-4 rounded shadow">
            <h2 class="text-center mb-4">Se connecter</h2>

            <?php if (!empty($message)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>

            <div class="text-center mt-3">
                <a href="register.php">Pas encore de compte ? S'inscrire</a>
            </div>
        </div>
    </div>
</div>