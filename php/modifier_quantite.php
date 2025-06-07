<?php
session_start();
require 'database.php';

$idpan = (int)($_POST['idpan'] ?? 0);
$action = $_POST['action'] ?? '';
$quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 0;

if ($idpan <= 0) {
    http_response_code(400);
    echo 0;
    exit;
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Récupérer la quantité actuelle dans la BDD (optionnel)

    if ($action === 'increase' || $action === 'decrease') {
        // Logique actuelle pour augmenter ou diminuer
        $stmt = $conn->prepare("SELECT quantite FROM panier WHERE idpan = ? AND id = ?");
        $stmt->bind_param("ii", $idpan, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $currentQty = $row['quantite'];
            if ($action === 'increase') {
                $newQty = $currentQty + 1;
            } else {
                $newQty = max(1, $currentQty - 1);
            }

            $update = $conn->prepare("UPDATE panier SET quantite = ? WHERE idpan = ? AND id = ?");
            $update->bind_param("iii", $newQty, $idpan, $user_id);
            $update->execute();

            echo $newQty;
        } else {
            echo 0;
        }
    } elseif ($action === 'set' && $quantite >= 1) {
        // Mise à jour directe de la quantité
        $update = $conn->prepare("UPDATE panier SET quantite = ? WHERE idpan = ? AND id = ?");
        $update->bind_param("iii", $quantite, $idpan, $user_id);
        $update->execute();

        echo $quantite;
    } else {
        echo 0;
    }

} else {
    // Panier session (invité)
    if (!isset($_SESSION['panier'][$idpan])) {
        echo 0;
        exit;
    }

    $currentQty = $_SESSION['panier'][$idpan]['quantite'];

    if ($action === 'increase') {
        $_SESSION['panier'][$idpan]['quantite'] = $currentQty + 1;
        echo $_SESSION['panier'][$idpan]['quantite'];
    } elseif ($action === 'decrease') {
        $_SESSION['panier'][$idpan]['quantite'] = max(1, $currentQty - 1);
        echo $_SESSION['panier'][$idpan]['quantite'];
    } elseif ($action === 'set' && $quantite >= 1) {
        $_SESSION['panier'][$idpan]['quantite'] = $quantite;
        echo $quantite;
    } else {
        echo $currentQty;
    }
}
exit;
