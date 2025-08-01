<?php
session_start();
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$message = '';

// Récupérer les offres de stage
$query = "SELECT * FROM offres_stage";
$stmt = $pdo->prepare($query);
$stmt->execute();
$offres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gestion de l'inscription à une offre
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply'])) {
    $etudiant_id = $_SESSION['id']; // Récupérer l'ID de l'étudiant depuis la session
    $offre_id = $_POST['offre_id']; // Récupérer l'ID de l'offre depuis le formulaire

    // Insérer l'inscription dans la table d'inscriptions
    $stmt = $pdo->prepare("INSERT INTO inscriptions (etudiant_id, offre_id) VALUES (:etudiant_id, :offre_id)");
    if ($stmt->execute([':etudiant_id' => $etudiant_id, ':offre_id' => $offre_id])) {
        // Stocker l'ID de l'offre dans la session
        $_SESSION['offre_id'] = $offre_id;
        header("Location: EtudiantController.php");
        exit();
    } else {
        $message = "Erreur lors de l'inscription à l'offre.";
    }
}

// Inclure la vue
include '../views/offres_stage.php';
?>