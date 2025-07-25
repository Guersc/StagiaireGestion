<?php
session_start();
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

// Vérifiez si la requête est POST et que l'ID du stage est défini
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stage_id'])) {
    // Récupérez l'ID du stage

    // Récupérer les données du formulaire
    $tache = trim($_POST['tache']);
    $stage_id = $_POST['stage_id'];

    // Vérifiez que la tâche n'est pas vide  
    if (!empty($tache)) {
        try {

            // Vérifiez si le stage existe dans la table des stages
            $stmt = $pdo->prepare("SELECT * FROM stages WHERE id = :stage_id");
            $stmt->execute([':stage_id' => $stage_id]);

            if ($stmt->rowCount() > 0) {
                // Insérer la tâche dans le carnet de stage
                $stmt = $pdo->prepare("INSERT INTO carnet_stage (stage_id, description, date) VALUES (:stage_id, :description, NOW())");
                $stmt->execute([':stage_id' => $stage_id, ':description' => $tache]);

                // Rediriger vers mon_stage.php avec un message de succès
                header("Location: mon_stage.php?message=Tâche ajoutée avec succès !");
                exit();
            } else {
                // Rediriger avec un message d'erreur si le stage n'existe pas
                header("Location: mon_stage.php?error=Le stage avec cet ID n'existe pas.");
                exit();
            }
        } catch (PDOException $e) { 
            // Afficher l'erreur SQL
            header("Location: mon_stage.php?error=Erreur de base de données : " . $e->getMessage());
            exit();
        }
    } else {
        // Rediriger avec un message d'erreur si la tâche est vide
        header("Location: mon_stage.php?error=La tâche ne peut pas être vide.");
        exit();
    }
} else {
    // Rediriger si l'accès à ce fichier est incorrect
    header("Location: mon_stage.php");
    exit();
}