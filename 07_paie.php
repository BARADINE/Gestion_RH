<?php
require_once '02_config.php';
if (!estConnecte()) { header('Location: 09_connexion.php'); exit; }


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function calculerSalaireNet($base, $primes, $deductions) {
        return $base + $primes - $deductions;
    }

    $base = (float)$_POST['salaire_base'];
    $primes = (float)$_POST['primes'];
    $deductions = (float)$_POST['deductions'];
    $snet = calculerSalaireNet($base, $primes, $deductions);

    $stmt = $pdo->prepare("INSERT INTO paie (id_employe, mois, annee, salaire_base, primes, deductions, salaire_net)
    VALUES (?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE primes=?, deductions=?, salaire_net=?");
    $stmt->execute([
        $_POST['id_emp'], $_POST['mois'], $_POST['annee'], $base, $primes, $deductions, $snet,
        $primes, $deductions, $snet
    ]);
    $msg = "<div class='alerte alerte-succes'>✅ Fiche enregistrée ! Net à payer : <strong>".number_format($snet,2,',',' ')." FCFA</strong></div>";
}
if (isset($_GET['imprimer'])) {
    list($id, $m, $a) = explode('-', $_GET['imprimer']);
    $req = $pdo->prepare("SELECT e.nom, e.prenom, e.poste, e.departement, p.* FROM paie p JOIN employes e ON p.id_employe=e.id WHERE p.id=? LIMIT 1");
    $req->execute([$id]);
    $fiche = $req->fetch();

    if (!$fiche) {
        die("<div style='text-align:center; margin-top:50px; font-size:18px; color:#c62828;'>❌ Fiche introuvable ! Retournez sur la liste et cliquez sur Imprimer depuis une fiche existante.</div>");
    }

    echo '<style>
    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            background: white !important;
            position: relative;
            margin: 0;
            padding: 20px;
        }
        body::before {
            content: "";
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100vw;
            height: 100vh;
            background-image: url("logo_univ_ndjamena.png");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 55% auto;
            opacity: 0.12;
            z-index: -9999;
            pointer-events: none;
        }
        button, .pas-imprimer { display: none !important; }
    }
    </style>';

    echo '<div style="max-width:800px; margin:0 auto; border:2px solid #0d47a1; padding:30px; background:white;">
        <div style="text-align:center; margin-bottom:30px;">
            <h2 style="margin:0; color:#0d47a1;">UNIVERSITÉ DE N\'DJAMENA</h2>
            <h3 style="margin:8px 0 0 0;">FICHE DE PAIE - '.str_pad($fiche['mois'],2,'0').'/'.$fiche['annee'].'</h3>
        </div>

        <p style="font-size:16px; margin:10px 0;"><strong>Employé :</strong> '.$fiche['nom'].' '.$fiche['prenom'].'</p>
        <p style="font-size:16px; margin:10px 0;"><strong>Poste / Département :</strong> '.$fiche['poste'].' - '.$fiche['departement'].'</p>
        <hr style="margin:15px 0; border:1px solid #eee;">

        <p style="font-size:16px; margin:8px 0;">Salaire base : <span style="float:right;">'.number_format($fiche['salaire_base'],2,',',' ').' FCFA</span></p>
        <p style="font-size:16px; margin:8px 0;">Primes : <span style="float:right;">'.number_format($fiche['primes'],2,',',' ').' FCFA</span></p>
        <p style="font-size:16px; margin:8px 0;">Déductions : <span style="float:right;">'.number_format($fiche['deductions'],2,',',' ').' FCFA</span></p>
        <hr style="margin:15px 0; border:1px solid #999;">

        <p style="font-size:18px; font-weight:bold; margin:10px 0;">NET À PAYER : <span style="float:right; font-size:20px; color:#0d47a1;">'.number_format($fiche['salaire_net'],2,',',' ').' FCFA</span></p>

        <div style="text-align:center; margin-top:30px;">
            <button onclick="window.print()" style="padding:10px 25px; background:#1976d2; color:white; border:none; border-radius:4px; font-size:15px; cursor:pointer;" class="pas-imprimer">🖨️ Imprimer</button>
            <br><br>
            <a href="03_index.php?vue=paie" class="pas-imprimer" style="color:#0d47a1;">← Retour à la liste</a>
        </div>
    </div>';
    exit;
}

$employes = $pdo->query("SELECT id, nom, prenom FROM employes ORDER BY nom")->fetchAll();
$paies = $pdo->query("SELECT p.*, CONCAT(e.nom,' ',e.prenom) AS emp FROM paie p JOIN employes e ON p.id_employe=e.id ORDER BY annee DESC, mois DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Paies - Université de N'Djamena</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:Arial, sans-serif; }
        body { background:#f0f4f8; max-width:1000px; margin:0 auto; padding:20px; }
        h2 { color:#0d47a1; margin-bottom:20px; }
        .alerte { padding:12px; border-radius:4px; margin-bottom:15px; }
        .alerte-succes { background:#e8f5e9; color:#2e7d32; }
        form { background:white; padding:20px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:25px; }
        .groupe-champ { margin-bottom:12px; }
        label { display:block; margin-bottom:5px; font-weight:600; }
        input, select { width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; font-size:15px; }
        button { background:#0d47a1; color:white; border:none; padding:10px 20px; border-radius:4px; cursor:pointer; font-size:15px; }
        table { width:100%; border-collapse:collapse; background:white; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
        th, td { padding:12px; text-align:left; border-bottom:1px solid #eee; }
        th { background:#e3f2fd; font-weight:bold; }
        .btn { padding:6px 12px; text-decoration:none; border-radius:4px; font-size:14px; display:inline-block; margin:2px; }
        .btn-bleu { background:#1976d2; color:white; }
    </style>
</head>
<body>
    <h2>💰 Gestion des Salaires & Fiches de Paie</h2>
    <?php if (isset($msg)) echo $msg; ?>

    <form method="post">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px,1fr)); gap:15px;">
            <div class="groupe-champ">
                <label>Employé</label>
                <select name="id_emp" required>
                    <?php foreach ($employes as $e): ?>
                    <option value="<?= $e['id'] ?>"><?= $e['nom'].' '.$e['prenom'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="groupe-champ">
                <label>Mois</label>
                <select name="mois" required>
                    <?php for($m=1;$m<=12;$m++): ?>
                    <option value="<?= $m ?>" <?= $m==date('n')?'selected':'' ?>><?= str_pad($m,2,'0') ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="groupe-champ">
                <label>Année</label>
                <input type="number" name="annee" value="<?= date('Y') ?>" required>
            </div>
            <div class="groupe-champ">
                <label>Salaire de base (FCFA)</label>
                <input type="number" step="0.01" name="salaire_base" required>
            </div>
            <div class="groupe-champ">
                <label>Primes (FCFA)</label>
                <input type="number" step="0.01" name="primes" value="0">
            </div>
            <div class="groupe-champ">
                <label>Déductions (FCFA)</label>
                <input type="number" step="0.01" name="deductions" value="0">
            </div>
        </div>
        <button type="submit">💾 Enregistrer la fiche</button>
    </form>

    <h3>Liste des fiches enregistrées</h3>
    <table>
        <tr>
            <th>Employé</th>
            <th>Période</th>
            <th>Net à payer</th>
            <th>Action</th>
        </tr>
        <?php foreach ($paies as $p): ?>
        <tr>
            <td><?= $p['emp'] ?></td>
            <td><?= str_pad($p['mois'],2,'0').'/'.$p['annee'] ?></td>
            <td><?= number_format($p['salaire_net'],2,',',' ').' FCFA' ?></td>
            <td><a href="?imprimer=<?= $p['id'].'-'.$p['mois'].'-'.$p['annee'] ?>" class="btn btn-bleu">🖨️ Imprimer</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

