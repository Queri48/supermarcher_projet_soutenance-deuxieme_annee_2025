<?php
session_start();
session_destroy();
if (isset($_GET['redirect'])) {
    $redirect = $_GET['redirect'];
    // déconnecter ici...
    header("Location: $redirect");
    exit;
}
header("Location: index.php"); // Redirige vers l'accueil après la déconnexion
exit();
?>
