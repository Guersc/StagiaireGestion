<?php
session_start();
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}

// Vérifiez si l'ID de l'étudiant est dans la session
if (!isset($_SESSION['id'])) {
    die("Erreur : L'identifiant de l'étudiant n'est pas disponible.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Démarrer un stage
        if ($action === 'demarrer_stage') {
            // Récupération des données du formulaire
            $nom_entreprise = $_POST['nom_entreprise'];
            $lieu_stage = $_POST['lieu_stage'];
            $adresse_entreprise = $_POST['adresse_entreprise'];
            $nom_encadreur = $_POST['nom_encadreur'];
            $poste_encadreur = $_POST['poste_encadreur'];
            $email_encadreur = $_POST['email_encadreur'];
            $telephone_encadreur = $_POST['telephone_encadreur'];
            $poste_stage = $_POST['poste_stage'];

            // Insertion des données dans la table stages
            $stmt = $pdo->prepare("INSERT INTO stages (etudiant_id, nom_entreprise, lieu_stage, adresse_entreprise, nom_encadreur, poste_encadreur, email_encadreur, telephone_encadreur, poste_stage) VALUES (:etudiant_id, :nom_entreprise, :lieu_stage, :adresse_entreprise, :nom_encadreur, :poste_encadreur, :email_encadreur, :telephone_encadreur, :poste_stage)");
            
            $stmt->execute([
                ':etudiant_id' => $_SESSION['id'], // Assurez-vous que l'ID est bien défini
                ':nom_entreprise' => $nom_entreprise,
                ':lieu_stage' => $lieu_stage,
                ':adresse_entreprise' => $adresse_entreprise,
                ':nom_encadreur' => $nom_encadreur,
                ':poste_encadreur' => $poste_encadreur,
                ':email_encadreur' => $email_encadreur,
                ':telephone_encadreur' => $telephone_encadreur,
                ':poste_stage' => $poste_stage
            ]);

            header("Location: mon_stage.php"); // Rediriger vers "Mon Stage"
            exit();
        }

        // Arrêter le stage
        if ($action === 'arreter_stage') {
            $stmt = $pdo->prepare("DELETE FROM stages WHERE etudiant_id = :etudiant_id");
            $stmt->execute([':etudiant_id' => $_SESSION['id']]);
            header("Location: mon_stage.php"); // Rediriger vers "Mon Stage"
            exit();
        }

        // Modifier les informations de stage
        if ($action === 'modifier_stage') {
            $stmt = $pdo->prepare("UPDATE stages SET nom_entreprise = :nom_entreprise, lieu_stage = :lieu_stage, adresse_entreprise = :adresse_entreprise, nom_encadreur = :nom_encadreur, poste_encadreur = :poste_encadreur, email_encadreur = :email_encadreur, telephone_encadreur = :telephone_encadreur, poste_stage = :poste_stage WHERE etudiant_id = :etudiant_id");
            
            $stmt->execute([
                ':nom_entreprise' => $_POST['nom_entreprise'],
                ':lieu_stage' => $_POST['lieu_stage'],
                ':adresse_entreprise' => $_POST['adresse_entreprise'],
                ':nom_encadreur' => $_POST['nom_encadreur'],
                ':poste_encadreur' => $_POST['poste_encadreur'],
                ':email_encadreur' => $_POST['email_encadreur'],
                ':telephone_encadreur' => $_POST['telephone_encadreur'],
                ':poste_stage' => $_POST['poste_stage'],
                ':etudiant_id' => $_SESSION['id'] // Assurez-vous que l'ID est bien défini
            ]);

            header("Location: mon_stage.php"); // Rediriger vers "Mon Stage"
            exit();
        }
    }
}

// Rediriger vers "Mon Stage" si aucune action n'est spécifiée
header("Location: mon_stage.php");
exit();

// Accepter un stage
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'accepter_stage') {
    $stage_id = $_POST['stage_id'];
    
    // Mettre à jour le statut du stage
    $stmt = $pdo->prepare("UPDATE stage SET statut = 'accepté' WHERE id = :id");
    $stmt->execute([':id' => $stage_id]);

    // Rediriger ou afficher un message de succès
    header("Location: voir_stages.php");
    exit();
}

// Refuser un stage
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'refuser_stage') {
    $stage_id = $_POST['stage_id'];
    
    // Mettre à jour le statut du stage
    $stmt = $pdo->prepare("UPDATE stage SET statut = 'refusé' WHERE id = :id");
    $stmt->execute([':id' => $stage_id]);

    // Récupérer l'email de l'étudiant
    $stmt = $pdo->prepare("SELECT email FROM utilisateurs WHERE id = (SELECT utilisateur_id FROM stage WHERE id = :stage_id)");
    $stmt->execute([':stage_id' => $stage_id]);
    $etudiant = $stmt->fetch();

    // Envoyer un email à l'étudiant
    if ($etudiant) {
        $to = $etudiant['email'];
        $subject = "Votre stage a été refusé";
        $message = "Nous vous informons que votre demande de stage a été refusée.";
        mail($to, $subject, $message);
    }

    // Rediriger ou afficher un message de succès
    header("Location: voir_stages.php");
    exit();
}
?>