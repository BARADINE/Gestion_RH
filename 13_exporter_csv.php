<?php
require_once '02_config.php';
if (!estConnecte()) { header('Location: 03_index.php'); exit; }


error_reporting(0);
ini_set('display_errors', 0);

$nom_fichier = "Export_RH_Universite_Ndjamena_".date('Y-m-d').".csv";


header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"".$nom_fichier."\"");
header("Pragma: no-cache");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

$sortie = fopen('php://output', 'w');

fwrite($sortie, chr(0xEF).chr(0xBB).chr(0xBF));

fputcsv($sortie, [], ';');
fputcsv($sortie, ["=== LISTE DES EMPLOYÉS ==="], ';');
fputcsv($sortie, ["ID", "Nom", "Prénom", "Email", "Poste", "Département", "Salaire Base (FCFA)", "Date Embauche", "ID Supérieur"], ';');

$emp = $pdo->query("SELECT * FROM employes ORDER BY nom")->fetchAll();
foreach ($emp as $e) {
    fputcsv($sortie, [
        $e['id'],
        $e['nom'],
        $e['prenom'],
        $e['email'],
        $e['poste'],
        $e['departement'],
        $e['salaire_base'],
        $e['date_embauche'],
        $e['id_superieur'] ?? 'Aucun'
    ], ';');
}


fputcsv($sortie, [], ';');
fputcsv($sortie, ["=== DEMANDES DE CONGÉS ==="], ';');
fputcsv($sortie, ["ID", "ID Employé", "Nom Employé", "Type Congé", "Date Début", "Date Fin", "Motif", "Statut", "Validé par"], ';');

$conges = $pdo->query("SELECT c.*, CONCAT(e.nom,' ',e.prenom) AS emp, CONCAT(v.nom,' ',v.prenom) AS valide 
                        FROM conges c JOIN employes e ON c.id_employe=e.id 
                        LEFT JOIN employes v ON c.id_validateur=v.id 
                        ORDER BY c.date_debut DESC")->fetchAll();
foreach ($conges as $c) {
    fputcsv($sortie, [
        $c['id'],
        $c['id_employe'],
        $c['emp'],
        $c['type'],
        $c['date_debut'],
        $c['date_fin'],
        $c['motif'],
        $c['statut'],
        $c['valide'] ?? 'Non validé'
    ], ';');
}


fputcsv($sortie, [], ';');
fputcsv($sortie, ["=== FICHES DE PAIE ==="], ';');
fputcsv($sortie, ["ID", "ID Employé", "Nom Employé", "Mois", "Année", "Salaire Base", "Primes", "Déductions", "Salaire Net"], ';');

$paie = $pdo->query("SELECT p.*, CONCAT(e.nom,' ',e.prenom) AS emp 
                     FROM paie p JOIN employes e ON p.id_employe=e.id 
                     ORDER BY annee DESC, mois DESC")->fetchAll();
foreach ($paie as $f) {
    fputcsv($sortie, [
        $f['id'],
        $f['id_employe'],
        $f['emp'],
        str_pad($f['mois'],2,'0',STR_PAD_LEFT),
        $f['annee'],
        $f['salaire_base'],
        $f['primes'],
        $f['deductions'],
        $f['salaire_net']
    ], ';');
}

fclose($sortie);
exit;
?>

