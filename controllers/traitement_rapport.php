<?php
session_start();
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['email']) || !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Vérifiez si un fichier a été téléchargé
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['rapport'])) {
    $etudiant_id = $_POST['etudiant_id'];
    $file = $_FILES['rapport'];

    // Vérifiez les erreurs de téléchargement
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Erreur lors du téléchargement du fichier.");
    }

    // Vérifiez le type de fichier
    $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
    if ($fileType !== 'pdf') {
        die("Seul les fichiers PDF sont autorisés.");
    }

    // Définir le chemin pour sauvegarder le fichier
    $uploadDir = '../uploads/'; // Assurez-vous que ce dossier existe et est accessible en écriture
    $fileName = uniqid() . '_' . basename($file['name']);
    $uploadFilePath = $uploadDir . $fileName;

    // Déplacez le fichier téléchargé
    if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
        // Enregistrer le chemin du fichier dans la base de données
        $stmt = $pdo->prepare("INSERT INTO rapports (etudiant_id, chemin) VALUES (:etudiant_id, :chemin)");
        $stmt->execute([':etudiant_id' => $etudiant_id, ':chemin' => $fileName]);

        header("Location: mon_stage.php?message=Rapport soumis avec succès !");
    } else {
        die("Erreur lors de la sauvegarde du fichier.");
    }
} else {
    header("Location: mon_stage.php");
    exit();
}