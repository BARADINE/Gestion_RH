<?php
require_once '02_config.php';
if (!estConnecte()) { header('Location: 09_connexion.php'); exit; }


$nom_fichier = "Export_RH_Universite_Ndjamena_".date('Y-m-d').".xls";

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$nom_fichier\"");
header("Pragma: no-cache");
header("Expires: 0");

echo chr(255).chr(254).mb_convert_encoding("SEP=;\n", 'UTF-16LE', 'UTF-8');


echo "\n\n";
echo "=== LISTE DES EMPLOYÉS ===\n";
echo "ID;Nom;Prénom;Email;Poste;Département;Salaire Base (FCFA);Date Embauche;ID Supérieur\n";

$emp = $pdo->query("SELECT * FROM employes ORDER BY nom")->fetchAll();
foreach ($emp as $e) {
    echo implode(";", [
        $e['id'],
        $e['nom'],
        $e['prenom'],
        $e['email'],
        $e['poste'],
        $e['departement'],
        $e['salaire_base'],
        $e['date_embauche'],
        $e['id_superieur'] ?? 'Aucun'
    ])."\n";
}


echo "\n\n";
echo "=== DEMANDES DE CONGÉS ===\n";
echo "ID;ID Employé;Employé;Type;Date Début;Date Fin;Motif;Statut;ID Validateur;Validé par\n";

$conges = $pdo->query("SELECT c.*, CONCAT(e.nom,' ',e.prenom) AS emp, CONCAT(v.nom,' ',v.prenom) AS valide 
                        FROM conges c JOIN employes e ON c.id_employe=e.id 
                        LEFT JOIN employes v ON c.id_validateur=v.id 
                        ORDER BY c.date_debut DESC")->fetchAll();
foreach ($conges as $c) {
    echo implode(";", [
        $c['id'],
        $c['id_employe'],
        $c['emp'],
        $c['type'],
        $c['date_debut'],
        $c['date_fin'],
        str_replace(';', ' - ', $c['motif']),
        $c['statut'],
        $c['id_validateur'] ?? '',
        $c['valide'] ?? ''
    ])."\n";
}


echo "\n\n";
echo "=== FICHES DE PAIE ===\n";
echo "ID;ID Employé;Employé;Mois;Année;Salaire Base;Primes;Déductions;Salaire Net\n";

$paie = $pdo->query("SELECT p.*, CONCAT(e.nom,' ',e.prenom) AS emp 
                     FROM paie p JOIN employes e ON p.id_employe=e.id 
                     ORDER BY annee DESC, mois DESC")->fetchAll();
foreach ($paie as $f) {
    echo implode(";", [
        $f['id'],
        $f['id_employe'],
        $f['emp'],
        str_pad($f['mois'],2,'0',STR_PAD_LEFT),
        $f['annee'],
        $f['salaire_base'],
        $f['primes'],
        $f['deductions'],
        $f['salaire_net']
    ])."\n";
}
exit;
?>

