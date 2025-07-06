<?php
// Sécurise les sorties HTML (empêche les failles XSS)
function e($string) {
    return htmlspecialchars(html_entity_decode($string), ENT_QUOTES, 'UTF-8');
}

