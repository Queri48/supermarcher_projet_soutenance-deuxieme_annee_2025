<?php
session_start();
session_destroy();
header("Location: ../index.php"); // Redirige vers l'accueil après la déconnexion
exit();
?>
