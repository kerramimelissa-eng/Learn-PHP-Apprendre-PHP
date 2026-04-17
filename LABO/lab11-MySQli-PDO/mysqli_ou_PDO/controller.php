<?php

require_once "db_management_mysqli.php";

// 🔒 Sécuriser l'accès (éviter erreurs si pas de POST)
$prenom   = $_POST['prenom']   ?? null;
$nom      = $_POST['nom']      ?? null;
$courriel = $_POST['courriel'] ?? null;

try {

    // ✅ 1. Créer la base et la table (une seule fois, mais sans danger si répété)
    $db = new Database();
    $db->createDatabaseStructure();

    // ✅ 2. Vérifier si on a des données du formulaire
    if ($prenom && $nom && $courriel) {

        // ✅ 3. Créer objet Employe
        $employe = new Employe($prenom, $nom, $courriel);

        // ✅ 4. Insérer
        $employe->insertEmploye();

        echo "<p>Employé ajouté avec succès</p>";
    }

    // ✅ 5. Afficher les employés
    $employeAffichage = new Employe("", "", "");
    $employeAffichage->afficherEmployes();

} catch (mysqli_sql_exception $erreur) {

    echo "<table border='1'>";
    echo "<tr><th>Type</th><th>Détail</th></tr>";

    echo "<tr><td>Message</td><td>" . $erreur->getMessage() . "</td></tr>";
    echo "<tr><td>Fichier</td><td>" . $erreur->getFile() . "</td></tr>";
    echo "<tr><td>Ligne</td><td>" . $erreur->getLine() . "</td></tr>";

    echo "</table>";
}
