<?php
session_start();

if (isset($_GET['idart']) && is_numeric($_GET['idart'])) {
    $idart = (int)$_GET['idart'];
    if (isset($_SESSION['panier'])) {
        foreach ($_SESSION['panier'] as $key => $item) {
            if ($item['idart'] === $idart) {
                unset($_SESSION['panier'][$key]);
                // Re-indexe le tableau pour éviter trous
                $_SESSION['panier'] = array_values($_SESSION['panier']);
                break;
            }
        }
    }
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
