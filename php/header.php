<?php
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
    'tableau_bord.php'=> 'Tableau de bord',
    'client.php'=> 'Client',
    'categorie.php'=> 'Catégories',
    'produit.php'=> 'Produits',
    'commande.php'=> 'Commandes',
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

        <li class="nav-item d-none d-md-block">
            <a href="panier.php" class="nav-link position-relative <?= ($currentPage == 'panier.php') ? 'active fw-bold text-primary' : 'text-dark' ?>">
                <i class="fas fa-shopping-cart"></i> Panier
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
                <li class="nav-item"><a href="panier.php" class="nav-link text-dark"><i class="fas fa-shopping-cart"></i> Panier</a></li>
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
<?php
}

function afficherHeaderclient($currentPage, $isLoggedIn)
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

        <li class="nav-item d-none d-md-block">
            <a href="panier.php" class="nav-link position-relative <?= ($currentPage == 'panier.php') ? 'active fw-bold text-primary' : 'text-dark' ?>">
                <i class="fas fa-shopping-cart"></i> Panier
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
                <li class="nav-item"><a href="panier.php" class="nav-link text-dark"><i class="fas fa-shopping-cart"></i> Panier</a></li>
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
            <a href="categorie.php" class="nav-link <?= ($currentPage == 'categorie.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-list"></i> Catégories</a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="produit.php" class="nav-link <?= ($currentPage == 'produit.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-cubes"></i> Produits</a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="promotion.php" class="nav-link <?= ($currentPage == 'promotion.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-gift"></i> Promotions</a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="contact.php" class="nav-link <?= ($currentPage == 'contact.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-phone"></i> Contact</a>
        </li>

        <li class="nav-item d-none d-md-block">
            <a href="commande.php" class="nav-link <?= ($currentPage == 'commande.php') ? 'active fw-bold text-primary' : 'text-dark' ?>"><i class="fas fa-clipboard-list"></i> Commandes</a>
        </li>

        <li class="nav-item dropdown d-none d-md-block">
            <a class="nav-link dropdown text-white  btn btn-primary" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-cog"></i> Paramètres
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                <li><a class="dropdown-item" href="admin.php">Ajouter compte administrateur</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="employe.php">Ajouter compte employé</a></li>
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
                <li class="nav-item"><a href="categorie.php" class="nav-link text-dark"><i class="fas fa-list"></i> Catégories</a></li>
                <li class="nav-item"><a href="produit.php" class="nav-link text-dark"><i class="fas fa-cubes"></i> Produits</a></li>
                <li class="nav-item"><a href="promotion.php" class="nav-link text-dark"><i class="fas fa-gift"></i> Promotions</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link text-dark"><i class="fas fa-phone"></i> Contact</a></li>
                <li class="nav-item"><a href="commande.php" class="nav-link text-dark"><i class="fas fa-clipboard-list"></i> Commandes</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown text-dark" href="#" id="userMenuMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i> Parametres
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userMenuMobile">
                        <li><a class="dropdown-item" href="admin.php">Ajouter compte administrateur</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="employe.php">Ajouter compte employé</a></li>
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
    <title><?= htmlspecialchars($title ?? "Super U", ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
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
        function validatePassword() {
            const password = document.getElementById("password").value;
            const cpassword = document.getElementById("cpassword").value;
            const regex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

            if (!regex.test(password)) {
                alert("Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.");
                return false;
            }

            if (password !== cpassword) {
                alert("Les mots de passe ne correspondent pas.");
                return false;
            }

            return true;
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