<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gestion_rh_n_djamena');

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch(PDOException $e) {
    die("<div style='color:red; padding:20px; text-align:center;'>
        Erreur connexion base de données : ".$e->getMessage()."
    </div>");
}

function nettoyer($valeur) {
    return htmlspecialchars(trim($valeur), ENT_QUOTES, 'UTF-8');
}


function estConnecte() {
    return isset($_SESSION['id_employe']);
}

function idEmployeConnecte() {
    return $_SESSION['id_employe'] ?? 0;
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

