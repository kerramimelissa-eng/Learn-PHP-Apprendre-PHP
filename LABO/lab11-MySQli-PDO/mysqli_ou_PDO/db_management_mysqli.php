<?php

class Database {

    private $host = HOSTNAME;
    private $username = USERNAME;
    private $password = PASSWORD;

    protected $connexion;

    // ✅ Constructeur
    public function __construct() {
        $this->connexion = new mysqli(
            $this->host,
            $this->username,
            $this->password
        );

        if ($this->connexion->connect_error) {
            die("Erreur connexion : " . $this->connexion->connect_error);
        }
    }

    // ✅ FONCTIONNALITÉ 1 : créer la base et la table
    public function createDatabaseStructure() {
        $sql = file_get_contents("db_structure.sql");
        $this->connexion->multi_query($sql);
    }

    // ✅ Accès protégé à la connexion
    protected function getConnection() {
        return $this->connexion;
    }

    // ✅ Destructeur
    public function __destruct() {
        if ($this->connexion) {
            $this->connexion->close();
        }
    }
}


class Employe extends Database {

    private $prenom;
    private $nom;
    private $courriel;

    // ✅ Constructeur
    public function __construct($prenom, $nom, $courriel) {
        parent::__construct();

        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->courriel = $courriel;

        // Sélection de la base
        $this->getConnection()->query("USE utilisateurs");
    }

    // ✅ FONCTIONNALITÉ 2 : INSERT (sans arguments)
    public function insertEmploye() {
        $conn = $this->getConnection();

        $sql = "INSERT INTO employes (prenom, nom, courriel) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sss", $this->prenom, $this->nom, $this->courriel);
        $stmt->execute();

        $stmt->close();
    }

    // ✅ FONCTIONNALITÉ 3 : SELECT + affichage
    public function afficherEmployes() {
        $conn = $this->getConnection();

        $result = $conn->query("SELECT * FROM employes");

        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Prénom</th><th>Nom</th><th>Courriel</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['prenom'] . "</td>";
            echo "<td>" . $row['nom'] . "</td>";
            echo "<td>" . $row['courriel'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";

        $result->close();
    }
}
