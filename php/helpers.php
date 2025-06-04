<?php
// Sécurise les sorties HTML (empêche les failles XSS)
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

