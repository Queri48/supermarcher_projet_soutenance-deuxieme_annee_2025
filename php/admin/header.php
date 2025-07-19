<?php
require_once '../helpers.php';
$currentPage = basename($_SERVER['SCRIPT_NAME']);

$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['user_role'] ?? null;

if (!$isLoggedIn) {
    header('Location: ../connexion.php');
    exit;
}

$pages = [
    'promotion.php' => 'Promotion',
    'contact.php' => 'Contact',
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
global $currentPage, $isLoggedIn;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? "Super U") ?></title>
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2poYy7oT65mFuxSJNjhoA8Ozg7NBJvHI&callback=initMap" async defer></script>
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

        .text-superu {
            color: #007d8f !important;
        }

        .vertical-divider {
            width: 2px;
            height: 25px;
            /* ou la hauteur souhaitée */
            background-color: #dee2e6;
            /* même couleur que dropdown-divider */
            margin: 0 10px;
            /* espace à gauche/droite */
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllGlobal = document.getElementById('selectAllGlobal');
            const catCheckboxes = document.querySelectorAll('.select-cat');
            const catSelectAlls = document.querySelectorAll('.select-cat-items');
            const productCheckboxes = document.querySelectorAll('.checkbox-article');

            // ✅ Tout sélectionner global
            selectAllGlobal.addEventListener('change', () => {
                const checked = selectAllGlobal.checked;

                // Coche toutes les catégories
                catCheckboxes.forEach(catCb => {
                    catCb.checked = checked;
                    const collapseTarget = document.querySelector(catCb.dataset.bsTarget);
                    if (checked) bootstrap.Collapse.getOrCreateInstance(collapseTarget).show();
                    else bootstrap.Collapse.getOrCreateInstance(collapseTarget).hide();
                });

                // Coche tous les articles
                productCheckboxes.forEach(cb => cb.checked = checked);

                // Coche tous les "sélectionner tout" par catégorie
                catSelectAlls.forEach(catAll => catAll.checked = checked);
            });

            // ✅ Sélectionner tout d’une seule catégorie
            catSelectAlls.forEach(catAll => {
                catAll.addEventListener('change', () => {
                    const idcat = catAll.dataset.idcat;
                    const checked = catAll.checked;
                    document.querySelectorAll(`.checkbox-article[data-idcat='${idcat}']`).forEach(cb => {
                        cb.checked = checked;
                    });
                });
            });

            // ✅ Affiche ou cache les articles selon la case catégorie cochée
            catCheckboxes.forEach(catCb => {
                catCb.addEventListener('change', () => {
                    const collapseTarget = document.querySelector(catCb.dataset.bsTarget);
                    if (catCb.checked) {
                        bootstrap.Collapse.getOrCreateInstance(collapseTarget).show();
                    } else {
                        bootstrap.Collapse.getOrCreateInstance(collapseTarget).hide();
                        const idcat = catCb.dataset.idcat;
                        document.querySelectorAll(`.checkbox-article[data-idcat='${idcat}']`).forEach(cb => cb.checked = false);
                        document.querySelector(`.select-cat-items[data-idcat='${idcat}']`).checked = false;
                    }
                });
            });
        });
    </script>


    <script>
        document.querySelectorAll(".select-cat").forEach(catCheckbox => {
            catCheckbox.addEventListener("change", () => {
                const idcat = catCheckbox.id.replace("cat-", "");
                const produits = document.querySelectorAll(".cat-" + idcat);
                produits.forEach(p => p.checked = catCheckbox.checked);
            });
        });
    </script>

    <script>
        // 👁️ Afficher/Masquer mot de passe
        const toggleIcon = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("password");

        if (toggleIcon && passwordInput) {
            toggleIcon.addEventListener("click", function() {
                const isPassword = passwordInput.type === "password";
                passwordInput.type = isPassword ? "text" : "password";

                this.classList.toggle("fa-eye");
                this.classList.toggle("fa-eye-slash");
            });
        }

        // 👁️ Afficher/Masquer confirmer mot de passe
        const ctoggleIcon = document.getElementById("togglecPassword");
        const cpasswordInput = document.getElementById("cpassword");

        if (ctoggleIcon && cpasswordInput) {
            ctoggleIcon.addEventListener("click", function() {
                const isPassword = cpasswordInput.type === "password";
                cpasswordInput.type = isPassword ? "text" : "password";

                this.classList.toggle("fa-eye");
                this.classList.toggle("fa-eye-slash");
            });
        }
    </script>

</head>

<body>
    <header>
        <?php global $pages; ?>
        <ul class="nav justify-content-between align-items-center bg-white px-2 py-2 shadow-sm flex-wrap flex-md-nowrap">

            <li class="nav-item order-md-0">
                <img src="../../images/Logo.png" alt="" class="imgh" style="height: 40px;">
            </li>

            <li class="nav-item order-0 d-md-none">
                <a href="<?= $currentPage ?>" class="nav-link active fw-bold text-primary"><?= $pages[$currentPage] ?? 'Accueil' ?></a>
            </li>

            <li class="nav-item d-none d-md-block">
                <a href="tableau_bord.php" class="nav-link <?= ($currentPage == 'tableau_bord.php') ? 'active fw-bold text-primary' : ' text-superu' ?>"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
            </li>

            <li class="d-md-none order-2 ms-auto">
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                    <i class="fas fa-bars"></i>
                </button>
            </li>

            <li class="nav-item d-none d-md-block">
                <a href="client.php" class="nav-link <?= ($currentPage == 'client.php') ? 'active fw-bold text-primary' : ' text-superu' ?>"><i class="fas fa-users"></i> Client</a>
            </li>

            <li class="nav-item d-none d-md-block">
                <a href="categorie.php" class="nav-link <?= ($currentPage == 'categorie.php') ? 'active fw-bold text-primary' : ' text-superu' ?>"><i class="fas fa-list"></i> Catégorie</a>
            </li>

            <li class="nav-item d-none d-md-block">
                <a href="produit.php" class="nav-link <?= ($currentPage == 'produit.php') ? 'active fw-bold text-primary' : ' text-superu' ?>"><i class="fas fa-cubes"></i> Produit</a>
            </li>

            <li class="nav-item d-none d-md-block">
                <a href="promotion.php" class="nav-link <?= ($currentPage == 'promotion.php') ? 'active fw-bold text-primary' : ' text-superu' ?>"><i class="fas fa-gift"></i> Promotion</a>
            </li>

            <li class="nav-item d-none d-md-block">
                <a href="contact.php" class="nav-link <?= ($currentPage == 'contact.php') ? 'active fw-bold text-primary' : ' text-superu' ?>"><i class="fas fa-phone"></i> Contact</a>
            </li>

            <li class="nav-item d-none d-md-block">
                <a href="commande.php" class="nav-link <?= ($currentPage == 'commande.php') ? 'active fw-bold text-primary' : ' text-superu' ?>"><i class="fas fa-clipboard-list"></i> Commande</a>
            </li>

            <li class="nav-item dropdown d-none d-md-block">
                <a class="nav-link dropdown text-white  btn btn-primary" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cog "></i> Paramètres
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                    <li><a class="dropdown-item text-superu" href="administrateur.php">Ajouter compte administrateur</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-superu" href="employer.php">Ajouter compte employé</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-superu" href="account.php">Paramètres du compte</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-superu" href="logout.php">Se déconnecter</a></li>
                </ul>
            </li>

            <div class="vertical-divider"></div>

            <li class="nav-item dropdown d-none d-md-block">
                <i class="fas fa-user-circle text-primary fw-bold"></i>
                <?php
                require '../database.php'; // pour $conn
                if (isset($_SESSION['user_id'])) {
                    $userid = $_SESSION['user_id'];
                    $stmt = $conn->prepare("SELECT nom, prenom FROM utilisateur WHERE id = ?");
                    $stmt->bind_param("i", $userid);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $usera = $result->fetch_assoc();
                    echo htmlspecialchars(e($usera['nom'])) . ' ' . e($usera['prenom']);
                } else {
                    echo 'Mon Compte';
                }
                ?>
            </li>

            <li class="nav-item d-none d-md-block">
            </li>

        </ul>

        <!-- Offcanvas menu mobile -->
        <div class="offcanvas offcanvas-end text-superu" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
            <div class="offcanvas-header">
                <h5 id="mobileMenuLabel">Menu</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body text-superu">
                <ul class="nav flex-column text-superu">
                    <li class="nav-item text-superu"><a href="tableau_bord.php" class="nav-link text-dark"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                    <li class="nav-item text-superu"><a href="client.php" class="nav-link text-dark"><i class="fas fa-users"></i> Client</a></li>
                    <li class="nav-item text-superu"><a href="categorie.php" class="nav-link text-dark"><i class="fas fa-list"></i> Catégorie</a></li>
                    <li class="nav-item text-superu"><a href="produit.php" class="nav-link text-dark"><i class="fas fa-cubes"></i> Produit</a></li>
                    <li class="nav-item text-superu"><a href="promotion.php" class="nav-link text-dark"><i class="fas fa-gift"></i> Promotion</a></li>
                    <li class="nav-item text-superu"><a href="contact.php" class="nav-link text-dark"><i class="fas fa-phone"></i> Contact</a></li>
                    <li class="nav-item text-superu"><a href="commande.php" class="nav-link text-dark"><i class="fas fa-clipboard-list"></i> Commande</a></li>
                    <li class="nav-item dropdown text-superu">
                        <a class="nav-link dropdown text-dark" href="#" id="userMenuMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog"></i> Parametres
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userMenuMobile">
                            <li><a class="dropdown-item text-superu" href="administrateur.php">Ajouter compte administrateur</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-superu" href="employer.php">Ajouter compte employé</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-superu" href="account.php">Paramètres du compte</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-superu" href="../logout.php">Se déconnecter</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <?php if (isset($_GET['annulation']) && $_GET['annulation'] === 'success'): ?>
        <div class="alert alert-warning text-center mt-3">
            Commande annulée avec succès.
        </div>
    <?php endif; ?>
</body>

</html>
