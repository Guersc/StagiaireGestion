<?php

require_once '../config.php';

class EtudiantController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function soumettreDemande($data) {
        // Vérification des données reçues
        if ($data['mot_de_passe'] !== $data['confirmation_mot_de_passe']) {
            throw new Exception("Les mots de passe ne correspondent pas.");
        }

        // Vérifier si l'étudiant existe déjà
        $stmt = $this->pdo->prepare("SELECT id FROM etudiants WHERE matricule = ? AND nom = ?");
        $stmt->execute([$data['matricule'], $data['nom']]);
        $etudiant_id = $stmt->fetchColumn();

        if ($etudiant_id) {
            $_SESSION['id'] = $etudiant_id; // Stocker l'ID dans la session
        } else {
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
                throw new Exception("Erreur lors de l'ajout de l'étudiant.");
            }

            $etudiant_id = $this->pdo->lastInsertId();
            $_SESSION['id'] = $etudiant_id;
        }

        // Redirection selon le type de formulaire
        if ($data['form_type'] === 'inscription_offre') {
            header('Location: ../views/demandeStage.php'); // Inscription à une offre
        } elseif ($data['form_type'] === 'demande_lettre') {
            // Ici, vous pourriez gérer l'insertion des informations de l'entreprise, si nécessaire
            header('Location: ../views/successLettre.php'); // Demande de lettre
        }
        exit();
    }

    public function traiterFormulaire() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type'])) {
            try {
                $this->soumettreDemande($_POST);
            } catch (Exception $e) {
                // Gérer l'erreur et afficher le formulaire avec un message d'erreur
                $error_message = $e->getMessage();
                include '../views/etudiant_form.php'; // Inclure la vue avec l'erreur
                return; // Sortir de la méthode
            }
        }

        // Afficher le formulaire par défaut
        include '../views/etudiant_form.php'; // Inclure la vue
    }
}

// Exemple d'utilisation
$controller = new EtudiantController($pdo);
$controller->traiterFormulaire();
?>