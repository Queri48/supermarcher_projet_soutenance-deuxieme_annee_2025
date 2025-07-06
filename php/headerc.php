<?php global $pages; ?>
<ul class="nav justify-content-between align-items-center bg-white px-2 py-2 shadow-sm flex-wrap flex-md-nowrap">

    <li class="nav-item order-md-0">
        <img src="../images/Logo.png" alt="" class="imgh" style="height: 40px;">
    </li>

    <li class="nav-item order-0 d-md-none">
        <a href="<?= $currentPage ?>" class="nav-link active fw-bold text-primary"><?= $pages[$currentPage] ?? 'Accueil' ?></a>
    </li>

    <li class="nav-item d-none d-md-block">
        <a href="index.php" class="nav-link <?= ($currentPage == 'index.php') ? 'active fw-bold text-primary' : 'text-superu' ?>">Accueil</a>
    </li>

    <li class="d-md-none order-2 ms-auto">
        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
            <i class="fas fa-bars"></i>
        </button>
    </li>

    <li class="nav-item d-none d-md-block">
        <a href="catalogue.php" class="nav-link <?= ($currentPage == 'catalogue.php') ? 'active fw-bold text-primary' : 'text-superu' ?>"><i class="fas fa-list"></i> Catalogue de produits</a>
    </li>

    <li class="nav-item d-none d-md-block">
        <a href="promotion.php" class="nav-link <?= ($currentPage == 'promotion.php') ? 'active fw-bold text-primary' : 'text-superu' ?>"><i class="fas fa-gift"></i> Promotions</a>
    </li>

    <li class="nav-item d-flex align-items-center flex-grow-1 mx-3 order-0" style="max-width: 245px;" id="lirech">
        <form class="input-group w-100" action="catalogue.php" method="GET">
            <input class="form-control" type="search" name="recherche" id="recherche" placeholder="Recherche" aria-label="Search" style="height: 45px;">
            <button class="btn btn-primary d-flex align-items-center justify-content-center" type="submit" style="height: 45px; width: 55px;">
                <i class="fas fa-search text-white"></i>
            </button>
        </form>
    </li>

    <li class="nav-item dropdown d-none d-md-block text-superu">
        <a class="nav-link text-white-important fw-bold text-superu" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle"></i>
            <?php
            require 'database.php'; // pour $conn
            if (isset($_SESSION['user_id'])) {
                $userid = $_SESSION['user_id'];
                $stmt = $conn->prepare("SELECT CONCAT(nom, ' ', prenom) AS fullname FROM Utilisateur WHERE id = ?");
                $stmt->bind_param("i", $userid);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                echo htmlspecialchars($user['fullname']);
            } else {
                echo 'Mon Compte';
            }
            ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end  text-superu" aria-labelledby="userMenu">
            <li><a class="dropdown-item  text-superu" href="account.php">Paramètres du compte</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item  text-superu" href="logout.php">Se déconnecter</a></li>
        </ul>
    </li>
    <li class="nav-item d-none d-md-block">
        <a href="historique.php" class="nav-link <?= ($currentPage == 'historique.php') ? 'active fw-bold text-primary' : 'text-superu' ?>"><i class="fas fa-history"></i> Historique</a>
    </li>

    <li class="nav-item d-none d-md-block">
        <a href="contact.php" class="nav-link <?= ($currentPage == 'contact.php') ? 'active fw-bold text-primary' : 'text-superu' ?>"><i class="fas fa-phone"></i> Contact</a>
    </li>

    <li class="nav-item me-2 text-superu">
        <a class="nav-link position-relative d-flex align-items-center" data-bs-toggle="offcanvas" href="#offcanvasPanier" role="button" aria-controls="offcanvasPanier">
            <i class="fas fa-shopping-cart fa-lg"></i>
            <span class="d-none d-md-inline ms-1">Panier</span>
            <?php
            require 'database.php'; // pour $conn

            $nbArticles = 0;
            if (isset($_SESSION['user_id'])) {
                $userid = $_SESSION['user_id'];
                $stmt = $conn->prepare("SELECT SUM(quantite) as total FROM panier WHERE id = ?");
                $stmt->bind_param("i", $userid);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $nbArticles = $row['total'] ?? 0;
            } elseif (isset($_SESSION['panier'])) {
                // pour non connecté, compter articles dans session
                $nbArticles = count($_SESSION['panier']);
            }

            echo '<span class="position-absolute top-1 start-60 translate-middle badge rounded-pill bg-danger">' . $nbArticles . '</span>';
            ?>

        </a>
    </li>
</ul>

<!-- Offcanvas menu mobile -->
<div class="offcanvas offcanvas-end text-primary" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
        <h5 id="mobileMenuLabel">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <!-- Ajouter Accueil ici -->
            <li class="nav-item"><a href="index.php" class="nav-link text-dark"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="nav-item"><a href="catalogue.php" class="nav-link text-dark"><i class="fas fa-list"></i> Catalogue de produits</a></li>
            <li class="nav-item"><a href="promotion.php" class="nav-link text-dark"><i class="fas fa-gift"></i> Promotions</a></li>
            <li class="nav-item"><a href="historique.php" class="nav-link text-dark"><i class="fas fa-history"></i> Historique</a></li>
            <li class="nav-item"><a href="contact.php" class="nav-link text-dark"><i class="fas fa-phone"></i> Contact</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown text-dark" href="#" id="userMenuMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
                    <?php
                    require 'database.php'; // pour $conn
                    if (isset($_SESSION['user_id'])) {
                        $userid = $_SESSION['user_id'];
                        $stmt = $conn->prepare("SELECT CONCAT(nom, ' ', prenom) AS fullname FROM Utilisateur WHERE id = ?");
                        $stmt->bind_param("i", $userid);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $user = $result->fetch_assoc();
                        echo htmlspecialchars($user['fullname']);
                    } else {
                        echo 'Mon Compte';
                    }
                    ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="userMenuMobile">
                    <li><a class="dropdown-item text-superu" href="account.php">Paramètres du compte</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-superu" href="logout.php">Se déconnecter</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

<!-- Offcanvas panier -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasPanier" aria-labelledby="offcanvasPanierLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasPanierLabel">Votre panier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <?php
        require 'database.php';
        $total = 0;

        if (isset($_SESSION['user_id'])) {
            $userid = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT p.idpan, a.titre, a.prix, a.image, p.quantite 
                            FROM panier p 
                            JOIN article a ON p.idart = a.idart 
                            WHERE p.id = ?");
            $stmt->bind_param("i", $userid);
            $stmt->execute();
            $result = $stmt->get_result();
            $panier = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $panier = [];

            if (isset($_SESSION['panier'])) {
                foreach ($_SESSION['panier'] as $item) {
                    $idart = $item['idart'];
                    $quantite = $item['quantite'];

                    // Récupère les détails de l'article
                    $stmt = $conn->prepare("SELECT idart, titre, prix, image FROM article WHERE idart = ?");
                    $stmt->bind_param("i", $idart);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $article = $res->fetch_assoc();

                    if ($article) {
                        $article['quantite'] = $quantite;
                        $panier[] = $article;
                    }
                }
            }
        }

        if (empty($panier)) {
            echo '<p>Votre panier est vide.</p>';
        } else {
            foreach ($panier as $produit) {
                $titre = e($produit['titre']);
                $prix = (float)$produit['prix'];
                $quantite = (int)$produit['quantite'];
                $totalProduit = $prix * $quantite;
                $total += $totalProduit;

                $image = base64_encode($produit['image']);

                $idpan = $produit['idpan'] ?? null;

                echo '
                    <div class="d-flex align-items-center mb-3 border-bottom pb-2">
                        <img src="data:image/jpeg;base64,' . $image . '" alt="' . $titre . '" style="width: 60px; height: 60px;" class="me-3 rounded">
                        <div class="flex-grow-1 w-150">
                            <h6 class="mb-1">' . $titre . '</h6>
                            <div class="input-group input-group-sm w-100">
                                <button class="btn btn-outline-secondary btn-decrease" data-idpan="' . $idpan . '">-</button>
                                <input type="text" class="form-control text-center qty-input" value="' . $quantite . '" data-idpan="' . $idpan . '" readonly>
                                <button class="btn btn-outline-secondary btn-increase" data-idpan="' . $idpan . '">+</button>
                            </div>
                            <small>Prix unitaire : ' . number_format($prix, 0, ',', ' ') . ' FCFA</small>
                        </div>
                        <div class="text-end w-50">
                            <strong>' . number_format($totalProduit, 0, ',', ' ') . ' FCFA</strong><br>
                            <a href="supprimer_panier.php?idpan=' . $idpan . '" class="btn btn-sm btn-link text-danger p-0 mt-1" title="Supprimer du panier">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>';
            }

            echo '<hr><div class="d-flex justify-content-between fw-bold mb-3">
                    <span>Total :</span>
                    <span>' . number_format($total, 0, ',', ' ') . ' FCFA</span>
                  </div>';
        }
        ?>

        <div class="text-center mt-4">
            <a href="commander.php" class="btn btn-success w-100">Commander</a>
        </div>
    </div>
</div>