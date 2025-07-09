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
function afficherHeader($role)
{
    if ($role === 2) {
        global $currentPage, $isLoggedIn;
        include 'headerc.php'; // Client
    } else {
        global $currentPage, $isLoggedIn;
        include 'headerv.php'; // Visiteur
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
    <script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2poYy7oT65mFuxSJNjhoA8Ozg7NBJvHI&callback=initMap" async defer></script>
    <link rel="stylesheet" href="../fontawesome-free-6.7.2-web/css/all.min.css">

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

        .text-superu .nav-link {
            color: #007d8f !important;
        }

        .btn-superu {
            background-color: #007d8f;
            color: #fff;
            border: 2px solid #007d8f;
            transition: all 0.3s ease;
            border-radius: 30px;
        }

        .btn-superu:hover,
        .btn-superu:focus {
            background-color: transparent;
            color: #fff;
            border-color: #fff;
        }

        .text-white-important {
            color: #ffffff !important;
        }

        .overlay {
            background-color: rgba(0, 0, 0, 0.25);
            /* noir avec 50% d’opacité */
            z-index: 1;
        }

        #carouselImageContainer {
            z-index: 1;
        }

        .carousel-caption {
            z-index: 2;
        }

        .carousel-background {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            z-index: 0;
        }

        .carousel-background.active {
            opacity: 1;
        }

        .carousel-caption {
            z-index: 1;
        }

        #customHeroCarousel {
            height: 400px;
        }

        @media (max-width: 768px) {
            #customHeroCarousel {
                height: 250px;
            }

            .carousel-caption {
                bottom: 30px !important;
                padding: 0 15px;
            }

            .carousel-caption h1 {
                font-size: 1.4rem;
            }

            .carousel-caption p {
                font-size: 1rem;
            }

            .carousel-caption a.btn {
                font-size: 0.9rem;
                padding: 10px 20px;
            }
        }

        /* Catégories, Produits & Promotions */
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        @media (max-width: 576px) {
            .card-img-top {
                height: 160px;
            }

            .card-title {
                font-size: 1rem;
            }

            .card-text {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 768px) {
            .row.text-center .col-md-4 {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }
        }

        .btn {
            white-space: nowrap;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 🎯 Gestion boutons +/-
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

            // 🔄 Reload page après fermeture offcanvas
            const offcanvas = document.getElementById('offcanvasPanier');
            if (offcanvas) {
                offcanvas.addEventListener('hidden.bs.offcanvas', function() {
                    location.reload();
                });
            }

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const imagePaths = [
                "../images/SuperUI2.jpg",
                "../images/SuperUI1.jpg",
                "../images/SuperUE2.jpg",
                "../images/SuperUE1.jpg"
            ];

            const container = document.getElementById("carouselImageContainer");

            imagePaths.forEach((src, index) => {
                const img = document.createElement("img");
                img.src = src;
                img.alt = `Image ${index + 1}`;
                img.className = "carousel-background";
                if (index === 0) img.classList.add("active");
                container.appendChild(img);
            });

            const images = container.querySelectorAll(".carousel-background");
            let currentIndex = 0;
            const interval = 5000; // 5 secondes

            setInterval(() => {
                images[currentIndex].classList.remove("active");
                currentIndex = (currentIndex + 1) % images.length;
                images[currentIndex].classList.add("active");
            }, interval);
        });
    </script>

</head>

<body>
    <header>
        <?php afficherHeader($isLoggedIn ? $userRole : null);
        if (isset($_SESSION['success_message'])): ?>
            <div id="notif" class="alert alert-success text-center position-fixed top-0 start-50 translate-middle-x mt-2 shadow text-superu" style="z-index: 1050;">
                <?= e($_SESSION['success_message']) ?>
            </div>
            <script>
                setTimeout(function() {
                    const notif = document.getElementById('notif');
                    if (notif) notif.remove();
                }, 3000); // disparaît après 3 secondes
            </script>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

    </header>
</body>

</html>