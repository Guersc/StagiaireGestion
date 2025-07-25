<?php
session_start();
require_once '../config.php';
include '../views/etudiant_form.php';

class EtudiantController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function soumettreDemande($data) {
        // Vérification des données reçues
        var_dump($data); // Debugging, à retirer après test

        // Vérifier si les mots de passe correspondent
        if ($data['mot_de_passe'] !== $data['confirmation_mot_de_passe']) {
            throw new Exception("Les mots de passe ne correspondent pas.");
        }

        // Vérifier si l'étudiant existe déjà
        $stmt = $this->pdo->prepare("SELECT id FROM etudiants WHERE matricule = ? AND nom = ?");
        $stmt->execute([$data['matricule'], $data['nom']]);
        $etudiant_id = $stmt->fetchColumn();

        if ($etudiant_id) {
            // L'étudiant existe déjà, stocker l'ID dans la session
            $_SESSION['id'] = $etudiant_id;
            header('Location: ../views/demandeStage.php'); // Redirection vers la demande de stage
            exit();
        }

        // Hachage du mot de passe
        $mot_de_passe_hache = password_hash($data['mot_de_passe'], PASSWORD_BCRYPT);

        // Ajouter l'étudiant dans la base de données
        $stmt = $this->pdo->prepare("INSERT INTO etudiants (matricule, nom, post_nom, prenom, genre, email, promotion, filiere, telephone, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt->execute([
            $data['matricule'],
            $data['nom'],
            $data['post_nom'],
            $data['prenom'],
            $data['genre'],
            $data['email'],
            $data['promotion'],
            $data['filiere'],
            $data['telephone'],
            $mot_de_passe_hache
        ])) {
            // Afficher les erreurs SQL
            print_r($stmt->errorInfo());
            throw new Exception("Erreur lors de l'ajout de l'étudiant.");
        }

        // Récupérer l'ID de l'étudiant après insertion
        $etudiant_id = $this->pdo->lastInsertId();
        $_SESSION['id'] = $etudiant_id; // Stocker l'ID dans la session

        // Redirection vers le fichier de demande de stage après ajout
        header('Location: ../views/demandeStage.php'); // Remplacez par le chemin correct
        exit();
    }

    public function traiterFormulaire() {
        // Vérifiez si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'etudiant') {
            try {
                $this->soumettreDemande($_POST);
            } catch (Exception $e) {
                // Gérer l'erreur (par exemple, afficher un message)
                $error_message = $e->getMessage();
                echo "<div class='error'>$error_message</div>"; // Afficher le message d'erreur
            }
        }
    }

    public function afficherFormulaire() {
        include '../views/etudiant_form.php'; // Inclure la vue
    }
}

// Exemple d'utilisation
$controller = new EtudiantController($pdo);
$controller->traiterFormulaire();
?>