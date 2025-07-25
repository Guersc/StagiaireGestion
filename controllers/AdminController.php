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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_offer'])) {
    $entreprise_nom = $_POST['entreprise_nom'];
    $entreprise_lieu = $_POST['entreprise_lieu'];
    $entreprise_adresse = $_POST['entreprise_adresse'];
    $description = $_POST['description'];
    $date_limite = $_POST['date_limite'];

    $stmt = $pdo->prepare("INSERT INTO offres_stage (entreprise_nom, entreprise_lieu, entreprise_adresse, description, date_limite) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$entreprise_nom, $entreprise_lieu, $entreprise_adresse, $description, $date_limite])) {
        $message = "Offre de stage créée avec succès.";
    } else {
        $message = "Erreur lors de la création de l'offre.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les demandes de stages depuis la base de données
    $query = "SELECT * FROM demandes_stages";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vous pouvez maintenant utiliser les demandes comme nécessaire,
    // par exemple, les passer à une vue ou les afficher.
    
    // Exemple d'affichage (peut être modifié selon vos besoins)
    foreach ($demandes as $demande) {
        echo "ID: " . htmlspecialchars($demande['id']) . "<br>";
        echo "ID Étudiant: " . htmlspecialchars($demande['Etudiant_id']) . "<br>";
        echo "Nom de l'Entreprise: " . htmlspecialchars($demande['entreprise_nom']) . "<br>";
        echo "Lieu: " . htmlspecialchars($demande['entreprise_lieu']) . "<br>";
        echo "Adresse: " . htmlspecialchars($demande['entreprise_adresse']) . "<br>";
        echo "Destinataire: " . htmlspecialchars($demande['destinateur']) . "<br><br>";
    }
}



// Récupérer les tâches des étudiants
$query = "SELECT c.*, e.nom, e.email FROM carnet_stage c JOIN etudiants e ON c.etudiant_id = e.id";
$stmt = $pdo->prepare($query);
$stmt->execute();
$carnets = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Inclure la vue
include '../views/admin_dashboard.php';
?>

