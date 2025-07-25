<?php
class Etudiant {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

   public function ajouterEtudiant($matricule, $nom, $post_nom, $prenom, $genre, $email, $promotion, $filiere, $telephone, $mot_de_passe) {
    $stmt = $this->pdo->prepare("INSERT INTO etudiants (matricule, nom, post_nom, prenom, genre, email, promotion, filiere, telephone, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt->execute([$matricule, $nom, $post_nom, $prenom, $genre, $email, $promotion, $filiere, $telephone, $mot_de_passe])) {
        // Afficher les erreurs SQL
        print_r($stmt->errorInfo());
        return false;
    }
    return true;
}
}
?>