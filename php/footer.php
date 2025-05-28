<?php
    $currentPage = basename($_SERVER['SCRIPT_NAME']); // Récupère le nom du fichier en cours
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : "ProTech Future Line"; ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <footer>
        <ul id="ul">
            <li><a href="index.php" class="<?= ($currentPage == 'index.php') ? 'active' : '' ?>">Accueil</a></li>
            <li><a href="articles.php" class="<?= ($currentPage == 'articles.php') ? 'active' : '' ?>">Articles</a></li>
            <li><a href="contact.php" class="<?= ($currentPage == 'contact.php') ? 'active' : '' ?>">Contact</a></li>
        </ul>
        <div id="footer">
            <div id="header">
                <img src="../images/Logo.png" alt="" class="imgf">
                <marquee>© 2025 Centre de Formation Professionnel "Les Métiers du Digital" Groupe 4 réaliser par HOUNSA Quéridas AMOUSSOU Andilath ISSOUFOU Noumane ZOUNON David</marquee>
            </div>
            <ul>
                <a href="https://www.facebook.com/"><li><img src="../images/facebook.png" alt="" class="imgfa"></li></a>
                <a href="https://www.instagram.com/"><li><img src="../images/instagram.png" alt="" class="imgi"></li></a>
                <a href="https://www.x.com/"><li><img src="../images/twitter.png" alt="" class="imgt"></li></a>
            </ul>
        </div>
    </footer>
</body>
</html>