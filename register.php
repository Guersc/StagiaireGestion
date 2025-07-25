<?php
session_start();
include 'config.php'; // Incluez votre fichier de configuration pour la connexion à la base de données

$message = ''; // Variable pour stocker le message d'état

// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Préparer et exécuter la requête SQL
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        // Authentification réussie
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role']; // Stocker le rôle dans la session

        // Rediriger selon le rôle
        switch ($user['role']) {
            case 'étudiant':
                header("Location: ../mon_stage.php"); // Redirige vers "Mon Stage"
                break;
            case 'responsable':
                header("Location: ../dashboard/responsable_dashboard.php"); // Page d'accueil des responsables
                break;
            case 'administrateur':
                header("Location: ../dashboard/admin_dashboard.php"); // Page d'accueil de l'administrateur
                break;
            default:
                header("Location: ../index.php"); // Redirection par défaut
                break;
        }
        exit();
    } else {
        $message = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion des Stagiaires</title>
    <link rel="stylesheet" href="assets/css/style_connexion.css">
</head>
<body>
    <header>
        <h1>Connexion</h1>
    </header>

    <main>
        <section>
            <form action="index.php" method="POST">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
                
                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                
                <button type="submit">Connexion</button>
            </form>
            <div id="message" style="color: red;"><?php echo $message; ?></div>
        </section>

       
    </main>

    
    <footer>
        <p>&copy; 2025 Universite Don Bosco de Lubumbashi. faire la difference.</p>
    </footer>
</body>
</html>