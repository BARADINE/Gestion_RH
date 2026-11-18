<?php require_once '02_config.php';
if (!estConnecte()) { header('Location: 09_connexion.php'); exit; }

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("INSERT INTO employes 
        (nom, prenom, email, poste, departement, salaire_base, date_embauche, id_superieur, mot_de_passe)
        VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            nettoyer($_POST['nom']),
            nettoyer($_POST['prenom']),
            nettoyer($_POST['email']),
            nettoyer($_POST['poste']),
            nettoyer($_POST['departement']),
            (float)$_POST['salaire_base'],
            $_POST['date_embauche'],
            !empty($_POST['id_superieur']) ? $_POST['id_superieur'] : NULL,
            '' // Pas de mot de passe demandé
        ]);
        $msg = "<div class='alerte alerte-succes'>✅ Employé inscrit avec succès !</div>";
    } catch (PDOException $e) {
        $msg = "<div class='alerte alerte-avert'>⚠️ Erreur : Email déjà utilisé ou supérieur introuvable</div>";
    }
}

$liste_sup = $pdo->query("SELECT id, nom, prenom, poste FROM employes ORDER BY nom")->fetchAll();
?>
<h2>➕ Inscrire un nouvel employé</h2>
<?= $msg ?>

<form method="post" style="margin-top:20px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px,1fr)); gap:15px;">
        <div class="groupe-champ">
            <label>Nom</label>
            <input type="text" name="nom" required>
        </div>
        <div class="groupe-champ">
            <label>Prénom</label>
            <input type="text" name="prenom" required>
        </div>
        <div class="groupe-champ">
            <label>Email professionnel</label>
            <input type="email" name="email" required>
        </div>
        <div class="groupe-champ">
            <label>Poste</label>
            <input type="text" name="poste" required>
        </div>
        <div class="groupe-champ">
            <label>Département</label>
            <input type="text" name="departement" required>
        </div>
        <div class="groupe-champ">
            <label>Salaire de base (FCFA)</label>
            <input type="number" step="0.01" name="salaire_base" required>
        </div>
        <div class="groupe-champ">
            <label>Date d'embauche</label>
            <input type="date" name="date_embauche" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="groupe-champ">
            <label>Supérieur hiérarchique</label>
            <select name="id_superieur">
                <option value="">Aucun (Direction)</option>
                <?php foreach ($liste_sup as $s): ?>
                <option value="<?= $s['id'] ?>"><?= $s['nom'].' '.$s['prenom'].' - '.$s['poste'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primaire">✅ Valider l'inscription</button>
</form>

