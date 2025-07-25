<?php
class DemandeStageController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer les demandes de stage d'un étudiant
    public function afficherDemandes($etudiant_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM demandes_stage WHERE etudiant_id = ?");
        $stmt->execute([$etudiant_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterDemande($entreprise_nom, $entreprise_lieu, $entreprise_adresse, $destinateur, $confirmation_notice) {
    // Vérifier si l'ID de l'étudiant est défini dans la session
    if (!isset($_SESSION['id'])) {
        throw new Exception("L'ID de l'étudiant n'est pas défini dans la session.");
    }

    $etudiant_id = $_SESSION['id']; // Récupérer l'ID de l'étudiant à partir de la session

    // Ajouter la demande de stage
    $stmt = $this->pdo->prepare("INSERT INTO demandes_stage (etudiant_id, entreprise_nom, entreprise_lieu, entreprise_adresse, destinateur, confirmation_notice) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt->execute([$etudiant_id, $entreprise_nom, $entreprise_lieu, $entreprise_adresse, $destinateur, $confirmation_notice])) {
        print_r($stmt->errorInfo());
        return false;
    }

    return true;
}
}
?>