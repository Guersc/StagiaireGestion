<?php
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

$email = 'guerschomngandu@gmail.com';
$mot_de_passe = 'monetoile123'; // Mot de passe en clair
$role = 'utilisateur'; // Changez ceci à 'admin' si nécessaire

// Hachage du mot de passe
$mot_de_passe_hashé = password_hash($mot_de_passe, PASSWORD_DEFAULT);

// Préparer et exécuter la requête d'insertion
$stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, role) VALUES (:email, :mot_de_passe, :role)");
$stmt->execute([':email' => $email, ':mot_de_passe' => $mot_de_passe_hashé, ':role' => $role]);

echo "Utilisateur ajouté avec succès.";
?>