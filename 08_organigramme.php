<h2>📂 Organigramme Hiérarchique</h2>
<?php
$liste = $pdo->query("SELECT id, nom, prenom, poste, id_superieur FROM employes ORDER BY nom")->fetchAll();
?>
<style>
.noeud { margin:8px 0; padding:8px 12px; background:#f5f9ff; border-left:3px solid #1976d2; border-radius:0 6px 6px 0; display:inline-block; min-width:250px; }
.branche { margin-left:25px; margin-top:5px; }
</style>

<div id="arbre"></div>
<script>
const employes = <?= json_encode($liste) ?>;
function creerArbre(parentId = null) {
    let html = '';
    employes.filter(e => e.id_superieur === parentId).forEach(emp => {
        html += `<div class="noeud"><strong>${emp.nom} ${emp.prenom}</strong><br>${emp.poste}</div>`;
        html += `<div class="branche">${creerArbre(emp.id)}</div>`;
    });
    return html;
}
document.getElementById('arbre').innerHTML = creerArbre();
</script>

