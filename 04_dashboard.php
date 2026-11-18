<h2>📊 Tableau de Bord RH</h2>
<?php
$nb_emp = $pdo->query("SELECT COUNT(*) FROM employes")->fetchColumn();
$nb_attente = $pdo->query("SELECT COUNT(*) FROM conges WHERE statut='en_attente'")->fetchColumn();
$nb_valides = $pdo->query("SELECT COUNT(*) FROM conges WHERE statut='valide'")->fetchColumn();
?>
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px,1fr)); gap:15px; margin:20px 0;">
    <div style="padding:20px; background:#e3f2fd; border-radius:8px; text-align:center;">
        <h3>Employés</h3>
        <p style="font-size:30px; font-weight:bold; margin-top:8px;"><?= $nb_emp ?></p>
    </div>
    <div style="padding:20px; background:#fff3e0; border-radius:8px; text-align:center;">
        <h3>Demandes en attente</h3>
        <p style="font-size:30px; font-weight:bold; margin-top:8px;"><?= $nb_attente ?></p>
    </div>
    <div style="padding:20px; background:#e8f5e9; border-radius:8px; text-align:center;">
        <h3>Congés validés</h3>
        <p style="font-size:30px; font-weight:bold; margin-top:8px;"><?= $nb_valides ?></p>
    </div>
</div>
<p style="margin-top:15px; color:#666;">Bienvenue dans l'application de gestion RH de l'Université de N'Djamena.</p>

