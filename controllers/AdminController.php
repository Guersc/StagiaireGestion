<?php
session_start();
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données
require_once '../modeles/Administrateur.php';


class AdminController {
    private $model;

    public function __construct($pdo) {
        $this->model = new Administrateur($pdo);
    }

    // Afficher les demandes de stages
    public function afficherDemandesStages() {
        $demandes = $this->model->getDemandesStages();
        include '../views/admin_dashboard.php'; // Inclure la vue
    }
    
}
$message = '';


// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: ../controllers/login.php");
    exit();
}

// Initialiser la requête pour récupérer les étudiants
$query = "SELECT * FROM etudiants";
$params = [];

// Vérifiez si une recherche a été effectuée
if (isset($_POST['search'])) {
    $search_term = $_POST['search_term'];
    $query .= " WHERE nom LIKE :search_term OR email LIKE :search_term"; // Filtrer par nom ou email
    $params[':search_term'] = "%" . $search_term . "%";
}

// Récupérer tous les étudiants
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajouter un nouvel étudiant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    $nom = $_POST['new_nom'];
    $post_nom = $_POST['new_post_nom'];
    $prenom = $_POST['new_prenom'];
    $genre = $_POST['new_genre'];
    $email = $_POST['new_email'];
    $mot_de_passe = password_hash($_POST['new_mot_de_passe'], PASSWORD_DEFAULT);
    $promotion = $_POST['new_promotion'];
    $filiere = $_POST['new_filiere'];
    $telephone = $_POST['new_telephone'];

    // Vérifiez si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->rowCount() == 0) {
        // Insérer le nouvel étudiant
        $stmt = $pdo->prepare("INSERT INTO etudiants (nom, post_nom, prenom, genre, email, promotion, filiere, telephone, mot_de_passe) VALUES (:nom, :post_nom, :prenom, :genre, :email, :promotion, :filiere, :telephone, :mot_de_passe)");
        $stmt->execute([':nom' => $nom, ':post_nom' => $post_nom, ':prenom' => $prenom, ':genre' => $genre, ':email' => $email, ':promotion' => $promotion, ':filiere' => $filiere, ':telephone' => $telephone, ':mot_de_passe' => $mot_de_passe]);
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $message = "L'email existe déjà.";
    }
}

// Modifier les informations d'un étudiant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_student'])) {
    $id = $_POST['student_id'];
    $nom = $_POST['edit_nom'];
    $post_nom = $_POST['edit_post_nom'];
    $prenom = $_POST['edit_prenom'];
    $genre = $_POST['edit_genre'];
    $email = $_POST['edit_email'];
    $promotion = $_POST['edit_promotion'];
    $filiere = $_POST['edit_filiere'];
    $telephone = $_POST['edit_telephone'];

    // Mettre à jour les informations de l'étudiant
    $stmt = $pdo->prepare("UPDATE etudiants SET nom = :nom, post_nom = :post_nom, prenom = :prenom, genre = :genre, email = :email, promotion = :promotion, filiere = :filiere, telephone = :telephone WHERE id = :id");
    $stmt->execute([':nom' => $nom, ':post_nom' => $post_nom, ':prenom' => $prenom, ':genre' => $genre, ':email' => $email, ':promotion' => $promotion, ':filiere' => $filiere, ':telephone' => $telephone, ':id' => $id]);
    header("Location: admin_dashboard.php");
    exit();
}

// Suppression d'un étudiant
if (isset($_GET['delete_id'])) {
    $delete_stmt = $pdo->prepare("DELETE FROM etudiants WHERE id = :id");
    $delete_stmt->execute([':id' => $_GET['delete_id']]);
    header("Location: admin_dashboard.php");
    exit();
}

$message = ''; // Initialiser le message

function recupererDemandesStage() {
    global $pdo;
    try {
        // Récupérer les demandes de stage avec les informations des étudiants
        $queryDemandes = "
            SELECT d.*, e.nom, e.post_nom, e.prenom 
            FROM demandes_stage d
            JOIN etudiants e ON d.etudiant_id = e.id";
        
        $stmtDemandes = $pdo->prepare($queryDemandes);
        $stmtDemandes->execute();
        $demandes = $stmtDemandes->fetchAll(PDO::FETCH_ASSOC);

        return $demandes;

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}



// Récupérer les tâches des étudiants
$query = "SELECT c.*, e.nom, e.email FROM carnet_stage c JOIN etudiants e ON c.etudiant_id = e.id";
$stmt = $pdo->prepare($query);
$stmt->execute();
$carnets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// partie de l'inscription aux offres 

function recupererInscriptions() {
    global $pdo;
    try {
        // Récupérer les données des étudiants
        $queryEtudiants = "SELECT * FROM etudiants";
        $stmtEtudiants = $pdo->prepare($queryEtudiants);
        $stmtEtudiants->execute();
        $etudiants = $stmtEtudiants->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les données des offres de stage
        $queryOffres = "SELECT * FROM offres_stage";
        $stmtOffres = $pdo->prepare($queryOffres);
        $stmtOffres->execute();
        $offres = $stmtOffres->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les inscriptions
        $queryInscriptions = "SELECT * FROM inscriptions";
        $stmtInscriptions = $pdo->prepare($queryInscriptions);
        $stmtInscriptions->execute();
        $inscriptions = $stmtInscriptions->fetchAll(PDO::FETCH_ASSOC);

        // Créer un tableau pour stocker les résultats
        $resultats = [];

        // Associer les informations des inscriptions avec les étudiants et les offres
        foreach ($inscriptions as $inscription) {
            $etudiantId = $inscription['etudiant_id'];
            $offreId = $inscription['offre_id'];

            // Trouver l'étudiant correspondant
            $etudiant = array_filter($etudiants, function($e) use ($etudiantId) {
                return $e['id'] == $etudiantId;
            });
            $etudiant = reset($etudiant); // Obtenir le premier élément trouvé

            // Trouver l'offre correspondante
            $offre = array_filter($offres, function($o) use ($offreId) {
                return $o['id'] == $offreId;
            });
            $offre = reset($offre); // Obtenir le premier élément trouvé

            // Ajouter les données au tableau des résultats
            if ($etudiant && $offre) {
                $resultats[] = [
                    'inscription_id' => $inscription['id'],
                    'nom_etudiant' => $etudiant['nom'],
                    'entreprise_nom' => $offre['entreprise_nom'] ?? 'Non spécifié', // Afficher le nom de l'entreprise
                ];
            }
        }

        return $resultats;

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}

// Appel de la fonction pour récupérer les inscriptions
$inscriptions = recupererInscriptions();




function compterEtudiants() {
    global $pdo;
    $query = "SELECT COUNT(*) AS total FROM etudiants";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function compterDemandesLetters() {
    global $pdo;
    $query = "SELECT COUNT(*) AS total FROM demandes_stage"; // Ajustez si nécessaire
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function compterInscriptionsOffres() {
    global $pdo;
    $query = "SELECT COUNT(*) AS total FROM inscriptions"; // Remplacez par votre table
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function compterStagesEnCours() {
    global $pdo;
    $query = "SELECT COUNT(*) AS total FROM stages WHERE statut = 'en cours'"; // Ajustez le critère
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function compterStagesTermines() {
    global $pdo;
    $query = "SELECT COUNT(*) AS total FROM stages WHERE statut = 'termine'"; // Ajustez le critère
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function compterRapportsDeposes() {
    global $pdo;
    $query = "SELECT COUNT(*) AS total FROM rapports WHERE statut = 'depose'"; // Ajustez le critère
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}



// Inclure la vue
include '../views/admin_dashboard.php';
?>

