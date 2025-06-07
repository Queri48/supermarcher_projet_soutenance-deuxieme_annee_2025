<?php
require_once 'helpers.php';
$currentPage = basename($_SERVER['SCRIPT_NAME']);

$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['user_role'] ?? null;

$pages = [
    'index.php' => 'Accueil',
    'catalogue.php' => 'Catalogues',
    'promotion.php' => 'Promotion',
    'contact.php' => 'Contact',
    'panier.php' => 'Panier',
    'historique.php' => 'Historique',
    'tableau_bord.php' => 'Tableau de bord',
    'categorie.php' => 'Catégories',
    'produit.php' => 'Produits',
    'commande.php' => 'Commandes',
    'connexion.php' => 'Connexion',
    'inscription.php' => 'Inscription',
    'logout.php' => 'Déconnexion',
    'administrateur.php' => 'Gestion Admin',
    'client.php' => 'Client',
    'employer.php' => 'Gestion Employé',
    'ajouter_admin.php' => 'Ajouter Admin',
    'ajouter_employe.php' => 'Ajouter Employé',
    'ajouter_categorie.php' => 'Ajouter Categorie',
    'ajouter_produit.php' => 'Ajouter Produit',
    'modifier_admin.php' => 'Modifier Admin',
    'modifier_client.php' => 'Modifier Client',
    'modifier_employe.php' => 'Modifier Employé',
    'modifier_categorie.php' => 'Modifier Categorie',
    'modifier_produit.php' => 'Modifier Produit',
    'supprimer_admin.php' => 'Supprimer Admin',
    'supprimer_client.php' => 'Supprimer Client',
    'supprimer_employe.php' => 'Supprimer Employé',
    'supprimer_contact.php' => 'Supprimer Contact',
    'supprimer_categorie.php' => 'Supprimer Categorie',
    'supprimer_produit.php' => 'Supprimer Produit',
    'account.php' => 'Mon Compte',
];

function afficherHeaderParDefaut($currentPage, $isLoggedIn)
{
    global $pages;
?>
    <ul class="nav justify-content-between align-items-center bg-white px-4 py-2 shadow-sm flex-wrap flex-md-nowrap">

        <li class="nav-item order-md-0">
            <img src="../images/Logo.png" alt="" class="imgh" style="height: 40px;">
        </li>

        <li class="nav-item order-0 d-md-none">
            <a href="<?= $currentPage ?>" class="nav-link active fw-bold text-primary"><?= $pages[$currentPage] ?? 'Accueil' ?></a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="index.php" class="nav-link <?= ($currentPage == 'index.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"> Accueil</a>
        </li>

        <li class="d-md-none order-2 ms-auto">
            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                <i class="fas fa-bars"></i>
            </button>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="catalogue.php" class="nav-link <?= ($currentPage == 'catalogue.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-list"></i> Catalogue de produits</a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="promotion.php" class="nav-link <?= ($currentPage == 'promotion.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-gift"></i> Promotions</a>
        </li>

        <li class="nav-item d-flex align-items-center flex-grow-1 mx-3 order-0" style="max-width: 335px;" id="lirech">
            <form class="input-group w-100" action="catalogue.php" method="GET">
                <input class="form-control" type="search" name="recherche" id="recherche" placeholder="Recherche" aria-label="Search" style="height: 45px;">
                <button class="btn btn-primary d-flex align-items-center justify-content-center" type="submit" style="height: 45px; width: 55px;">
                    <i class="fas fa-search text-white"></i>
                </button>
            </form>
        </li>

        <li class="nav-item dropdown d-none d-md-block">
            <a class="nav-link dropdown text-dark" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user"></i> Mon Compte
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                <li><a class="dropdown-item" href="connexion.php">Se connecter</a></li>
                <li><a class="dropdown-item" href="inscription.php">Créer un compte</a></li>
            </ul>
        </li>

        <li class="nav-item d-none d-md-block">
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="contact.php" class="nav-link <?= ($currentPage == 'contact.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-phone"></i> Contact</a>
        </li>

        <li class="nav-item me-2">
            <a class="nav-link position-relative d-flex align-items-center" data-bs-toggle="offcanvas" href="#offcanvasPanier" role="button" aria-controls="offcanvasPanier">
                <i class="fas fa-shopping-cart fa-lg"></i>
                <span class="d-none d-md-inline ms-1">Panier</span>
                <?php
                $nbArticles = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;
                echo '<span class="position-absolute top-1 start-60 translate-middle badge rounded-pill bg-danger">' . $nbArticles . '</span>';
                ?>
            </a>
        </li>
    </ul>

    <!-- Offcanvas menu mobile -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
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
                <li class="nav-item"><a href="contact.php" class="nav-link text-dark"><i class="fas fa-phone"></i> Contact</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown text-dark" href="#" id="userMenuMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> Mon Compte
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userMenuMobile">
                        <li><a class="dropdown-item" href="connexion.php">Se connecter</a></li>
                        <li><a class="dropdown-item" href="inscription.php">Créer un compte</a></li>
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
                <p class="text-muted">Connectez-vous pour finaliser votre commande :</p>
                <a href="connexion.php" class="btn btn-primary w-100 mb-2">Se connecter</a>
                <a href="inscription.php" class="btn btn-outline-secondary w-100">Créer un compte</a>
            </div>
        </div>
    </div>

<?php
}

function afficherHeaderclient($currentPage, $isLoggedIn)
{
    global $pages;
?>
    <ul class="nav justify-content-between align-items-center bg-white px-2 py-2 shadow-sm flex-wrap flex-md-nowrap">

        <li class="nav-item order-md-0">
            <img src="../images/Logo.png" alt="" class="imgh" style="height: 40px;">
        </li>

        <li class="nav-item order-0 d-md-none">
            <a href="<?= $currentPage ?>" class="nav-link active fw-bold text-primary"><?= $pages[$currentPage] ?? 'Accueil' ?></a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="index.php" class="nav-link <?= ($currentPage == 'index.php') ? 'active fw-bold text-primary' : 'text-dark' ?>">Accueil</a>
        </li>

        <li class="d-md-none order-2 ms-auto">
            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                <i class="fas fa-bars"></i>
            </button>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="catalogue.php" class="nav-link <?= ($currentPage == 'catalogue.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-list"></i> Catalogue de produits</a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="promotion.php" class="nav-link <?= ($currentPage == 'promotion.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-gift"></i> Promotions</a>
        </li>

        <li class="nav-item d-flex align-items-center flex-grow-1 mx-3 order-0" style="max-width: 245px;" id="lirech">
            <form class="input-group w-100" action="catalogue.php" method="GET">
                <input class="form-control" type="search" name="recherche" id="recherche" placeholder="Recherche" aria-label="Search" style="height: 45px;">
                <button class="btn btn-primary d-flex align-items-center justify-content-center" type="submit" style="height: 45px; width: 55px;">
                    <i class="fas fa-search text-white"></i>
                </button>
            </form>
        </li>

        <li class="nav-item dropdown d-none d-md-block">
            <a class="nav-link dropdown text-dark btn btn-primary text-white fw-bold" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle"></i></i> Mon Compte
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                <li><a class="dropdown-item" href="account.php">Paramètres du compte</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
            </ul>
        </li>
        <li class="nav-item d-none d-md-block">
            <a href="historique.php" class="nav-link <?= ($currentPage == 'historique.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-history"></i> Historique</a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="contact.php" class="nav-link <?= ($currentPage == 'contact.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-phone"></i> Contact</a>
        </li>

        <li class="nav-item me-2">
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
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
                        <i class="fas fa-user-circle"></i> Mon Compte
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userMenuMobile">
                        <li><a class="dropdown-item" href="account.php">Paramètres du compte</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
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

<?php
}

function afficherHeaderadmin($currentPage, $isLoggedIn)
{
    global $pages;
?>
    <ul class="nav justify-content-between align-items-center bg-white px-4 py-2 shadow-sm flex-wrap flex-md-nowrap">

        <li class="nav-item order-md-0">
            <img src="../../images/Logo.png" alt="" class="imgh" style="height: 40px;">
        </li>

        <li class="nav-item order-0 d-md-none">
            <a href="<?= $currentPage ?>" class="nav-link active fw-bold text-primary"><?= $pages[$currentPage] ?? 'Accueil' ?></a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="tableau_bord.php" class="nav-link <?= ($currentPage == 'tableau_bord.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
        </li>

        <li class="d-md-none order-2 ms-auto">
            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                <i class="fas fa-bars"></i>
            </button>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="client.php" class="nav-link <?= ($currentPage == 'client.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-users"></i> Client</a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="categorie.php" class="nav-link <?= ($currentPage == 'categorie.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-list"></i> Catégorie</a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="produit.php" class="nav-link <?= ($currentPage == 'produit.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-cubes"></i> Produit</a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="promotion.php" class="nav-link <?= ($currentPage == 'promotion.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-gift"></i> Promotion</a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="contact.php" class="nav-link <?= ($currentPage == 'contact.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-phone"></i> Contact</a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="commande.php" class="nav-link <?= ($currentPage == 'commande.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-clipboard-list"></i> Commande</a>
        </li>

        <li class="nav-item dropdown d-none d-md-block">
            <a class="nav-link dropdown text-white  btn btn-primary" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-cog"></i> Paramètres
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                <li><a class="dropdown-item" href="administrateur.php">Ajouter compte administrateur</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="employer.php">Ajouter compte employé</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="account.php">Paramètres du compte</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
            </ul>
        </li>

        <li class="nav-item d-flex align-items-center flex-grow-1 mx-1 order-0" style="max-width: 160px;" id="lirech">
            <form class="input-group w-100" action="catalogue.php" method="GET">
                <input class="form-control" type="search" name="recherche" id="recherche" placeholder="Recherche" aria-label="Search" style="height: 45px;">
                <button class="btn btn-primary d-flex align-items-center justify-content-center" type="submit" style="height: 45px; width: 45px;">
                    <i class="fas fa-search text-white"></i>
                </button>
            </form>
        </li>

        <li class="nav-item d-none d-md-block">
        </li>
    </ul>

    <!-- Offcanvas menu mobile -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header">
            <h5 id="mobileMenuLabel">Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <!-- Ajouter Accueil ici -->
                <li class="nav-item"><a href="tableau_bord.php" class="nav-link text-dark"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                <li class="nav-item"><a href="client.php" class="nav-link text-dark"><i class="fas fa-users"></i> Client</a></li>
                <li class="nav-item"><a href="categorie.php" class="nav-link text-dark"><i class="fas fa-list"></i> Catégorie</a></li>
                <li class="nav-item"><a href="produit.php" class="nav-link text-dark"><i class="fas fa-cubes"></i> Produit</a></li>
                <li class="nav-item"><a href="promotion.php" class="nav-link text-dark"><i class="fas fa-gift"></i> Promotion</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link text-dark"><i class="fas fa-phone"></i> Contact</a></li>
                <li class="nav-item"><a href="commande.php" class="nav-link text-dark"><i class="fas fa-clipboard-list"></i> Commande</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown text-dark" href="#" id="userMenuMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i> Parametres
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userMenuMobile">
                        <li><a class="dropdown-item" href="administrateur.php">Ajouter compte administrateur</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="employer.php">Ajouter compte employé</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="account.php">Paramètres du compte</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="../logout.php">Se déconnecter</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
<?php
}

// Fonction pour inclure le header selon le rôle
function includeHeaderByRole($role)
{
    switch ($role) {
        case '0':
            global $currentPage, $isLoggedIn;
            afficherHeaderadmin($currentPage, $isLoggedIn);
            break;
        case '1':
            include 'header_employe.php';
            break;
        case '2':
            global $currentPage, $isLoggedIn;
            afficherHeaderclient($currentPage, $isLoggedIn);
            break;
        default:
            global $currentPage, $isLoggedIn;
            afficherHeaderParDefaut($currentPage, $isLoggedIn);
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? "Super U") ?></title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2poYy7oT65mFuxSJNjhoA8Ozg7NBJvHI&callback=initMap" async defer></script>
    <link rel="stylesheet" href="../fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../../fontawesome-free-6.7.2-web/css/all.min.css">

    <style>
        @media (max-width: 768px) {
            #lirech {
                width: 20px;
            }

            #recherche {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }

            .input-group>.btn {
                padding: 0.2rem 0.5rem;
            }
        }

        :root {
            --bs-primary: red;
            --bs-secondary: rgb(213, 0, 0);
            --bs-success: #28a745;
            --bs-info: #17a2b8;
            --bs-warning: #ffc107;
            --bs-danger: #dc3545;
            --bs-light: #f8f9fa;
            --bs-dark: #343a40;
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-primary:hover {
            background-color: var(--bs-secondary);
            border-color: var(--bs-secondary);
        }

        body .text-primary {
            color: var(--bs-primary) !important;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll(".btn-decrease, .btn-increase");

            buttons.forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();

                    const idpan = this.dataset.idpan;
                    const action = this.classList.contains("btn-increase") ? "increase" : "decrease";
                    const input = document.querySelector(`.qty-input[data-idpan='${idpan}']`);

                    fetch("modifier_quantite.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `idpan=${idpan}&action=${action}`
                        })
                        .then(response => {
                            if (!response.ok) throw new Error("Erreur serveur");
                            return response.text();
                        })
                        .then(newQty => {
                            input.value = newQty;
                        })
                        .catch(error => {
                            console.error("Erreur AJAX:", error);
                        });
                });
            });

            // ➕ Recharge la page après fermeture de l'offcanvas
            const offcanvas = document.getElementById('offcanvasPanier');
            offcanvas.addEventListener('hidden.bs.offcanvas', function() {
                location.reload();
            });
        });
    </script>

    <script>
        let map, marker;

        function initMap() {
            const defaultLocation = {
                lat: 6.3703,
                lng: 2.3912
            }; // Exemple : Cotonou

            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLocation,
                zoom: 14,
            });

            marker = new google.maps.Marker({
                position: defaultLocation,
                map,
                draggable: true,
            });

            // Mettre à jour le champ adresse automatiquement
            google.maps.event.addListener(marker, 'dragend', function() {
                const pos = marker.getPosition();
                document.getElementById("adresse").value = `${pos.lat()},${pos.lng()}`;
            });

            // Bouton pour obtenir l'emplacement actuel
            document.getElementById("get-location").addEventListener("click", function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        const location = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        map.setCenter(location);
                        marker.setPosition(location);
                        document.getElementById("adresse").value = `${location.lat},${location.lng}`;
                    }, () => alert("Impossible de récupérer votre position."));
                } else {
                    alert("La géolocalisation n'est pas supportée par ce navigateur.");
                }
            });
        }
    </script>

</head>

<body>
    <header>
        <?php
        if ($isLoggedIn) {
            includeHeaderByRole($userRole);
        } else {
            afficherHeaderParDefaut($currentPage, $isLoggedIn);
        }
        ?>
    </header>
</body>

</html>