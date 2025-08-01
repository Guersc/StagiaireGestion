<?php
session_start(); // Assurez-vous de démarrer la session
require_once '../config.php'; // Assurez-vous que le chemin est correct
require_once '../controllers/EtudiantController.php'; // Inclure le contrôleur de l'étudiant

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

    // Ajouter une demande de stage
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

    // Rediriger vers le contrôleur de l'étudiant pour récupérer les informations
    public function redirigerVersEtudiant() {
        $etudiantController = new EtudiantController($this->pdo);
     // Appeler la méthode pour afficher le formulaire de l'étudiant
    }
}

// Exemple d'utilisation
$controller = new DemandeStageController($pdo);

// Vous pouvez appeler la méthode de redirection ici
$controller->redirigerVersEtudiant();
?>