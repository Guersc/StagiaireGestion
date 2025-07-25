<?php
class DemandeStage {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function afficherDemandes($etudiant_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM demandes WHERE etudiant_id = ?");
        $stmt->execute([$etudiant_id]);
        return $stmt->fetchAll();
    }

    public function ajouterDemande($etudiant_id, $entreprise_nom, $entreprise_lieu, $entreprise_adresse, $destinateur, $confirmation_notice) {
        $stmt = $this->pdo->prepare("INSERT INTO demandes (etudiant_id, entreprise_nom, entreprise_lieu, entreprise_adresse, destinateur, confirmation_notice) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$etudiant_id, $entreprise_nom, $entreprise_lieu, $entreprise_adresse, $destinateur, $confirmation_notice]);
    }
}
?>