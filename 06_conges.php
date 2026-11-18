<h2>🏖️ Gestion des Congés</h2>
<?php
$id_connecte = idEmployeConnecte();
$infos_connecte = $pdo->query("SELECT id, id_superieur FROM employes WHERE id=$id_connecte")->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['demander'])) {
    $stmt = $pdo->prepare("INSERT INTO conges (id_employe, type, date_debut, date_fin, motif) VALUES (?,?,?,?,?)");
    $stmt->execute([$id_connecte, $_POST['type'], $_POST['date_debut'], $_POST['date_fin'], nettoyer($_POST['motif'])]);
    echo "<div class='alerte alerte-succes'>✅ Demande envoyée en attente de validation</div>";
}

if (isset($_GET['action']) && in_array($_GET['action'], ['valider','refuser']) && isset($_GET['id_conge'])) {
    $dem = $pdo->query("SELECT id_employe FROM conges WHERE id=".(int)$_GET['id_conge'])->fetch();
    $sup_demandeur = $pdo->query("SELECT id_superieur FROM employes WHERE id=".$dem['id_employe'])->fetchColumn();
    $peut_agir = ($sup_demandeur == $id_connecte) || ($infos_connecte['id_superieur'] === null);
    
    if ($peut_agir) {
        $statut = $_GET['action'] == 'valider' ? 'valide' : 'refuse';
        $pdo->prepare("UPDATE conges SET statut=?, id_validateur=? WHERE id=?")->execute([$statut, $id_connecte, (int)$_GET['id_conge']]);
        echo "<div class='alerte alerte-succes'>✅ Congé $statut avec succès</div>";
    } else {
        echo "<div class='alerte alerte-avert'>⚠️ Vous n'avez pas le droit de valider cette demande</div>";
    }
}

$liste_conges = $pdo->query("SELECT c.*, CONCAT(e.nom,' ',e.prenom) AS employe, CONCAT(v.nom,' ',v.prenom) AS validateur FROM conges c JOIN employes e ON c.id_employe=e.id LEFT JOIN employes v ON c.id_validateur=v.id ORDER BY c.date_debut DESC")->fetchAll();
?>

<h3>Demander un congé</h3>
<form method="post" style="margin-bottom:25px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px,1fr)); gap:15px;">
        <div class="groupe-champ">
            <label>Type</label>
            <select name="type" required>
                <option value="annuel">Congé annuel</option>
                <option value="maladie">Congé maladie</option>
                <option value="exceptionnel">Congé exceptionnel</option>
            </select>
        </div>
        <div class="groupe-champ"><label>Début</label><input type="date" name="date_debut" min="<?= date('Y-m-d') ?>" required></div>
        <div class="groupe-champ"><label>Fin</label><input type="date" name="date_fin" min="<?= date('Y-m-d') ?>" required></div>
    </div>
    <div class="groupe-champ"><label>Motif</label><textarea name="motif" rows="3" required></textarea></div>
    <button type="submit" name="demander" class="btn btn-primaire">Envoyer la demande</button>
</form>

<h3>Liste des demandes</h3>
<table>
    <tr><th>Employé</th><th>Type</th><th>Période</th><th>Statut</th><th>Validé par</th><th>Actions</th></tr>
    <?php foreach ($liste_conges as $c):
        $sup_dem = $pdo->query("SELECT id_superieur FROM employes WHERE id=".$c['id_employe'])->fetchColumn();
        $peut_agir = ($sup_dem == $id_connecte) || ($infos_connecte['id_superieur'] === null);
    ?>
    <tr>
        <td><?= $c['employe'] ?></td>
        <td><?= ucfirst($c['type']) ?></td>
        <td>Du <?= date('d/m/Y', strtotime($c['date_debut'])) ?> au <?= date('d/m/Y', strtotime($c['date_fin'])) ?></td>
        <td>
            <?php
            if ($c['statut'] == 'en_attente') echo '<span style="color:#f57c00;">En attente</span>';
            elseif ($c['statut'] == 'valide') echo '<span style="color:#2e7d32;">Validé</span>';
            else echo '<span style="color:#c62828;">Refusé</span>';
            ?>
        </td>
        <td><?= $c['validateur'] ?: '-' ?></td>
        <td>
            <?php if ($c['statut'] == 'en_attente' && $peut_agir): ?>
                <a href="?vue=conges&action=valider&id_conge=<?= $c['id'] ?>" class="btn btn-vert">Valider</a>
                <a href="?vue=conges&action=refuser&id_conge=<?= $c['id'] ?>" class="btn btn-rouge">Refuser</a>
            <?php else: ?>
                <em>Aucune action</em>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

