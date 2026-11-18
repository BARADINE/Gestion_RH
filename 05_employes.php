<h2>👥 Gestion des Employés</h2>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouter'])) {
        $stmt = $pdo->prepare("INSERT INTO employes 
        (nom, prenom, email, poste, departement, salaire_base, date_embauche, id_superieur, mot_de_passe)
        VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            nettoyer($_POST['nom']), nettoyer($_POST['prenom']), nettoyer($_POST['email']),
            nettoyer($_POST['poste']), nettoyer($_POST['departement']), (float)$_POST['salaire_base'],
            $_POST['date_embauche'], !empty($_POST['id_superieur']) ? $_POST['id_superieur'] : NULL,
            password_hash('employe123', PASSWORD_DEFAULT)
        ]);
        echo "<div class='alerte alerte-succes'>✅ Employé ajouté (mot de passe par défaut : employe123)</div>";
    }
    if (isset($_POST['modifier'])) {
        $stmt = $pdo->prepare("UPDATE employes SET nom=?, prenom=?, email=?, poste=?, departement=?, salaire_base=?, date_embauche=?, id_superieur=? WHERE id=?");
        $stmt->execute([
            nettoyer($_POST['nom']), nettoyer($_POST['prenom']), nettoyer($_POST['email']),
            nettoyer($_POST['poste']), nettoyer($_POST['departement']), (float)$_POST['salaire_base'],
            $_POST['date_embauche'], !empty($_POST['id_superieur']) ? $_POST['id_superieur'] : NULL,
            (int)$_POST['id_emp']
        ]);
        echo "<div class='alerte alerte-succes'>✅ Employé modifié</div>";
    }
}
if (isset($_GET['supprimer'])) {
    $pdo->prepare("DELETE FROM employes WHERE id=?")->execute([(int)$_GET['supprimer']]);
    echo "<div class='alerte alerte-avert'>🗑️ Employé supprimé</div>";
}

$edit = null;
if (isset($_GET['modifier'])) $edit = $pdo->query("SELECT * FROM employes WHERE id=".(int)$_GET['modifier'])->fetch();
$liste = $pdo->query("SELECT e.*, CONCAT(s.nom,' ',s.prenom) AS superieur FROM employes e LEFT JOIN employes s ON e.id_superieur=s.id ORDER BY e.nom")->fetchAll();
?>

<h3><?= $edit ? "Modifier l'employé" : "Ajouter un employé" ?></h3>
<form method="post" style="margin-bottom:25px;">
    <?php if ($edit): ?><input type="hidden" name="id_emp" value="<?= $edit['id'] ?>"><?php endif; ?>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px,1fr)); gap:15px;">
        <div class="groupe-champ"><label>Nom</label><input type="text" name="nom" value="<?= $edit['nom']??'' ?>" required></div>
        <div class="groupe-champ"><label>Prénom</label><input type="text" name="prenom" value="<?= $edit['prenom']??'' ?>" required></div>
        <div class="groupe-champ"><label>Email</label><input type="email" name="email" value="<?= $edit['email']??'' ?>" required></div>
        <div class="groupe-champ"><label>Poste</label><input type="text" name="poste" value="<?= $edit['poste']??'' ?>" required></div>
        <div class="groupe-champ"><label>Département</label><input type="text" name="departement" value="<?= $edit['departement']??'' ?>" required></div>
        <div class="groupe-champ"><label>Salaire base (FCFA)</label><input type="number" step="0.01" name="salaire_base" value="<?= $edit['salaire_base']??'' ?>" required></div>
        <div class="groupe-champ"><label>Date embauche</label><input type="date" name="date_embauche" value="<?= $edit['date_embauche']??date('Y-m-d') ?>" required></div>
        <div class="groupe-champ">
            <label>Supérieur hiérarchique</label>
            <select name="id_superieur">
                <option value="">Aucun (Direction)</option>
                <?php foreach ($liste as $s): if (!$edit || $s['id'] != $edit['id']): ?>
                <option value="<?= $s['id'] ?>" <?= (@$edit['id_superieur'] == $s['id']) ? 'selected' : '' ?>>
                    <?= $s['nom'].' '.$s['prenom'].' - '.$s['poste'] ?>
                </option>
                <?php endif; endforeach; ?>
            </select>
        </div>
    </div>
    <button type="submit" name="<?= $edit?'modifier':'ajouter' ?>" class="btn btn-primaire"><?= $edit?'Enregistrer':'Ajouter' ?></button>
    <?php if ($edit): ?><a href="?vue=employes" class="btn btn-rouge">Annuler</a><?php endif; ?>
</form>

<h3>Liste du personnel</h3>
<table>
    <tr><th>Nom & Prénom</th><th>Poste</th><th>Département</th><th>Supérieur</th><th>Salaire</th><th>Actions</th></tr>
    <?php foreach ($liste as $e): ?>
    <tr>
        <td><?= $e['nom'].' '.$e['prenom'] ?></td>
        <td><?= $e['poste'] ?></td>
        <td><?= $e['departement'] ?></td>
        <td><?= $e['superieur'] ?: '<strong>Direction</strong>' ?></td>
        <td><?= number_format($e['salaire_base'],2,',',' ') ?> FCFA</td>
        <td>
            <a href="?vue=employes&modifier=<?= $e['id'] ?>" class="btn btn-orange">Modifier</a>
            <a href="?vue=employes&supprimer=<?= $e['id'] ?>" class="btn btn-rouge" onclick="return confirm('Confirmer ?')">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

