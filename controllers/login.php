<?php
session_start();
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

$message = ''; // Variable pour stocker le message d'état


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifiez les informations d'identification
    $stmt = $pdo->prepare("SELECT * FROM Etudiants WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        // Enregistrez l'email et l'ID dans la session
        $_SESSION['email'] = $user['email'];
        $_SESSION['id'] = $user['id']; // Enregistrez également l'ID pour une utilisation ultérieure
        header("Location: ../views/mon_stage.php"); // Rediriger vers le profil
        exit();
    } else {
        $error_message = "Identifiants incorrects.";
    }
}

// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérification dans la table etudiants
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        // Authentification réussie pour un étudiant
        $_SESSION['email'] = $user['email'];
        $_SESSION['id'] = $user['id']; // Stocker l'ID de l'utilisateur dans la session

        header("Location: ../views/mon_stage.php"); // Redirige vers "Mon Stage"
        exit();
    } elseif ($user && password_verify($mot_de_passe, $user['mot_de_passe'])){
        // Si l'utilisateur n'est pas trouvé dans etudiants, vérifier dans tuteurs
        $stmt = $pdo->prepare("SELECT * FROM tuteurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $tuteur = $stmt->fetch();

        if ($tuteur && password_verify($mot_de_passe, $tuteur['mot_de_passe'])) {
            // Authentification réussie pour un tuteur
            $_SESSION['email'] = $tuteur['email'];
            $_SESSION['id'] = $tuteur['id']; // Stocker l'ID du tuteur dans la session

            header("Location: ../dashboard/tuteur_dashboard.php"); // Redirige vers la page d'accueil du tuteur
            exit();
        } else {
            // Si aucun utilisateur n'est trouvé
            $message = "Email ou mot de passe incorrect.";
        }
    }else{
         // Si aucun utilisateur n'est trouvé
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
    <link rel="stylesheet" href="../assets/css/styleConnexion.css">
</head>
<body>
    <header>
        <h1>Connexion</h1>
    </header>

    <main>
        <section>
            <form action="login.php" method="POST">
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
        <p>&copy; 2025 Université Don Bosco de Lubumbashi. Faire la différence.</p>
    </footer>
</body>
</html>