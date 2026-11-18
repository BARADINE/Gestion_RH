<?php require_once '02_config.php';

if (isset($_GET['deconnecter'])) {
    session_destroy();
    header('Location: 09_connexion.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = nettoyer($_POST['email']);
    
    $util = $pdo->prepare("SELECT id FROM employes WHERE email=? LIMIT 1");
    $util->execute([$email]);
    $utilisateur = $util->fetch();
    
    if ($utilisateur) {
        $_SESSION['id_employe'] = $utilisateur['id'];
        header('Location: 03_index.php');
        exit;
    } else {
        $erreur = "Cet email n'existe pas dans la base";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion RH</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:Arial, sans-serif; }
        body { background:#f0f4f8; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:15px; }
        .cadre { background:white; padding:30px; border-radius:8px; box-shadow:0 2px 15px rgba(0,0,0,0.1); width:100%; max-width:400px; }
        .cadre h2 { text-align:center; color:#0d47a1; margin-bottom:20px; }
        .groupe-champ { margin-bottom:15px; }
        label { display:block; margin-bottom:5px; font-weight:600; }
        input { width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; font-size:15px; }
        button { width:100%; padding:12px; background:#0d47a1; color:white; border:none; border-radius:4px; font-size:16px; cursor:pointer; margin-top:5px; }
        .erreur { background:#ffebee; color:#c62828; padding:10px; border-radius:4px; margin-bottom:15px; text-align:center; }
        .info { margin-top:15px; text-align:center; font-size:14px; color:#666; }
    </style>
</head>
<body>
    <div class="cadre">
        <h2>🏛️ Connexion RH</h2>
        <?php if (isset($erreur)) echo "<div class='erreur'>$erreur</div>"; ?>
        <form method="post">
            <div class="groupe-champ">
                <label>Email de l'utilisateur</label>
                <input type="email" name="email" required value="admin@univ-ndjamena.td">
            </div>
            <button type="submit">Accéder à l'application</button>
        </form>
        <div class="info">
            Compte administrateur : bahradinatiebechir@gmail.com <br>
            (Aucun mot de passe n'est demandé)
        </div>
    </div>
</body>
</html>

