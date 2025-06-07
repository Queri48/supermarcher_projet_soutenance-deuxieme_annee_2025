<?php
session_start();
$title = "Créer un compte";
require 'database.php';

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
    $role = 2;
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
        // Vérifie si l'email est déjà utilisé
        $stmt_check_email = $conn->prepare("SELECT * FROM utilisateur WHERE email = ? AND valide = 1");
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $result = $stmt_check_email->get_result();

        if ($result->num_rows > 0) {
            $message = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
        } else {
            $stmt_check_email->close();

            // Hachage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertion de l'utilisateur
            $stmt = $conn->prepare("INSERT INTO utilisateur (nom, prenom, email, tel, adresse, password, valide, role, datetime) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssis", $nom, $prenom, $email, $tel, $adresse, $hashed_password, $valide, $role, $datetime);

            if ($stmt->execute()) {
                $user_id = $stmt->insert_id; // Récupère l'ID nouvellement créé
                $stmt->close();

                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_role'] = $role;

                // Fusion panier session → base
                if (isset($_SESSION['panier'])) {
                    foreach ($_SESSION['panier'] as $item) {
                        $idart = $item['idart'];
                        $quantite = $item['quantite'];

                        // Vérifie si le produit est déjà dans le panier BDD
                        $stmt_check = $conn->prepare("SELECT quantite FROM panier WHERE id = ? AND idart = ?");
                        $stmt_check->bind_param("ii", $user_id, $idart);
                        $stmt_check->execute();
                        $result_check = $stmt_check->get_result();

                        if ($row = $result_check->fetch_assoc()) {
                            $nouvelle_quantite = $row['quantite'] + $quantite;
                            $stmt_update = $conn->prepare("UPDATE panier SET quantite = ? WHERE id = ? AND idart = ?");
                            $stmt_update->bind_param("iii", $nouvelle_quantite, $user_id, $idart);
                            $stmt_update->execute();
                        } else {
                            $stmt_insert = $conn->prepare("INSERT INTO panier (id, idart, quantite) VALUES (?, ?, ?)");
                            $stmt_insert->bind_param("iii", $user_id, $idart, $quantite);
                            $stmt_insert->execute();
                        }
                    }

                    // Nettoie le panier session après fusion
                    unset($_SESSION['panier']);
                }
                // Redirection uniquement pour les clients
                $redirect = $_SESSION['redirect_after_login'] ?? 'index.php';
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
                exit;
            } else {
                $message = "Erreur lors de l'inscription : " . $stmt->error;
            }
        }
    }
}

include 'header.php';
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
                    <label for="cpassword" class="form-label">Confirmer Mot de passe</label>
                    <input type="password" class="form-control" name="cpassword" id="cpassword" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
            </form>

            <div class="text-center mt-3">
                <a href="login.php">Déjà un compte ? Se connecter</a>
            </div>
        </div>
    </div>
</div>