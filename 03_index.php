<?php

require_once '02_config.php';


if (!estConnecte()) {
    header('Location: 09_connexion.php');
    exit;
}


$page = $_GET['vue'] ?? 'dashboard';
$pages_autorisees = ['dashboard','employes','conges','paie','organigramme','inscription'];
if (!in_array($page, $pages_autorisees)) $page = 'dashboard';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion RH - Université de N'Djamena</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Arial, sans-serif; }
        body { background:#f0f4f8; max-width:1200px; margin:0 auto; padding:15px; }
        .entete { background:#0d47a1; color:white; padding:20px; border-radius:8px; text-align:center; margin-bottom:20px; }
        .entete h1 { font-size:22px; margin:8px 0; }
        .nav { display:flex; flex-wrap:wrap; justify-content:center; gap:8px; margin-bottom:20px; }
        .nav a { padding:10px 16px; background:#1976d2; color:white; text-decoration:none; border-radius:5px; transition:0.2s; }
        .nav a:hover, .nav a.actif { background:#0d47a1; }
        .contenu { background:white; padding:25px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.08); }
        .btn { padding:8px 14px; border:none; border-radius:4px; text-decoration:none; display:inline-block; cursor:pointer; margin:3px; }
        .btn-primaire { background:#1976d2; color:white; }
        .btn-vert { background:#2e7d32; color:white; }
        .btn-orange { background:#f57c00; color:white; }
        .btn-rouge { background:#c62828; color:white; }
        table { width:100%; border-collapse:collapse; margin:15px 0; }
        th, td { padding:12px; text-align:left; border-bottom:1px solid #eee; }
        th { background:#e3f2fd; }
        .groupe-champ { margin-bottom:15px; }
        label { display:block; margin-bottom:5px; font-weight:600; }
        input, select, textarea { width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; font-size:15px; }
        .alerte { padding:12px; border-radius:5px; margin:10px 0; }
        .alerte-succes { background:#e8f5e9; color:#2e7d32; }
        .alerte-avert { background:#fff3e0; color:#f57c00; }
        .deconnexion { margin-top:10px; display:inline-block; }
        @media print { .nav, .deconnexion, .pas-imprimer { display:none; } body { background:white; } }


@media print {
    body::before {
        content: "";
        position: fixed;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 100%; height: 100%;
        background: url('logo_univ_ndjamena.png') center center no-repeat;
        background-size: 55% auto;
        opacity: 0.3; /* Ajustez cette valeur : 0.05 = très clair, 0.2 = plus visible */
        z-index: -1;
    }
    .pas-imprimer { display: none !important; }
    body { background: white; }
}


    </style>
</head>
<body>
    <div class="entete">
        <h2>🏛️ UNIVERSITÉ DE N'DJAMENA</h2>
        <h1>APPLICATION DE GESTION DES RESSOURCES HUMAINES</h1>
        <a href="09_connexion.php?deconnecter=1" class="btn btn-rouge deconnexion pas-imprimer">🔒 Se déconnecter</a>
    </div>

    <div class="nav pas-imprimer">
        <a href="?vue=dashboard" class="<?= $page=='dashboard'?'actif':'' ?>">Tableau de bord</a>
        <a href="?vue=employes" class="<?= $page=='employes'?'actif':'' ?>">Employés</a>
        <a href="?vue=conges" class="<?= $page=='conges'?'actif':'' ?>">Congés</a>
        <a href="?vue=paie" class="<?= $page=='paie'?'actif':'' ?>">Paie</a>
        <a href="?vue=organigramme" class="<?= $page=='organigramme'?'actif':'' ?>">Organigramme</a>
        <a href="?vue=inscription" class="<?= $page=='inscription'?'actif':'' ?>">➕ Inscrire Employé</a>
        <a href="?vue=exporter_excel" class="<?= $page=='exporter_excel'?'actif':'' ?>">excel</a>
        <a href="?vue=exporter_csv" class="<?= $page=='exporter_csv'?'actif':'' ?>">fichier csv</a>
       
    </div>

    <div class="contenu">
        <?php
       
        switch($page) {
            case 'dashboard': include "04_dashboard.php"; break;
            case 'employes': include "05_employes.php"; break;
            case 'conges': include "06_conges.php"; break;
            case 'paie': include "07_paie.php"; break;
            case 'organigramme': include "08_organigramme.php"; break;
            case 'inscription': include "11_inscription.php"; break;
            case 'exporter_excel': include "12_exporter_excel.php"; break;
            case 'exporter_csv': include "13_exporter_csv.php.php"; break;
            default: echo "<h3>Page introuvable</h3>";
        }
        ?>
    </div>
</body>
</html>

