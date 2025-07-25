<?php
session_start();
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: ../controllers/login.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}

// Récupérer l'email de l'étudiant depuis la session
$email = $_SESSION['email'];

// Récupérer les informations de l'étudiant à partir de la table Etudiants
$etudiant_stmt = $pdo->prepare("SELECT * FROM Etudiants WHERE email = :email");
$etudiant_stmt->execute([':email' => $email]);
$etudiant = $etudiant_stmt->fetch(PDO::FETCH_ASSOC);

// Vérifiez si l'étudiant existe
if (!$etudiant) {
    die("L'étudiant n'existe pas."); // Affiche un message d'erreur si l'étudiant n'existe pas
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'modifier_etudiant') {
        // Récupérer les données du formulaire
        $matricule = $_POST['matricule'];
        $nom = $_POST['nom'];
        $post_nom = $_POST['post_nom'];
        $prenom = $_POST['prenom'];
        $genre = $_POST['genre'];
        $email = $_POST['email'];
        $promotion = $_POST['promotion'];
        $filiere = $_POST['filiere'];
        $telephone = $_POST['telephone'];

        // Mettre à jour les informations de l'étudiant
        $stmt = $pdo->prepare("UPDATE Etudiants SET matricule = :matricule, nom = :nom, post_nom = :post_nom, prenom = :prenom, genre = :genre, email = :email, promotion = :promotion, filiere = :filiere, telephone = :telephone WHERE email = :current_email");
        if ($stmt->execute([
            ':current_email' => $email,
            ':matricule' => $matricule,
            ':nom' => $nom,
            ':post_nom' => $post_nom,
            ':prenom' => $prenom,
            ':genre' => $genre,
            ':email' => $email,
            ':promotion' => $promotion,
            ':filiere' => $filiere,
            ':telephone' => $telephone,
        ])) {
            header("Location: ../controllers/ProfilControlleur.php?message=Modifications enregistrées.");
            exit();
        } else {
            $error_message = "Erreur lors de la mise à jour des informations.";
        }
    } elseif ($_POST['action'] === 'annuler') {
        header("Location: ../controllers/ProfilControlleur.php");
        exit();
    } elseif ($_POST['action'] === 'reset_password') {
        $ancien_mot_de_passe = $_POST['ancien_mot_de_passe'];
        $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];

        if (password_verify($ancien_mot_de_passe, $etudiant['mot_de_passe'])) {
            $stmt = $pdo->prepare("UPDATE Etudiants SET mot_de_passe = :mot_de_passe WHERE email = :email");
            if ($stmt->execute([
                ':email' => $email,
                ':mot_de_passe' => password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT),
            ])) {
                header("Location: ../controllers/ProfilControlleur.php?message=Mot de passe réinitialisé avec succès.");
                exit();
            }
        } else {
            $error_message = "L'ancien mot de passe est incorrect.";
        }
    }
}

// Inclure la vue
include '../views/profil.php';
?>