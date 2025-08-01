<?php
class Administrateur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer toutes les demandes de stages
    public function getDemandesStages() {
        $query = "SELECT * FROM demandes_stages";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Vous pouvez ajouter d'autres méthodes si nécessaire
    
}

?>
