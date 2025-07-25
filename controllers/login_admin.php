<?php
session_start();
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

$message = ''; // Pour stocker les messages d'état

/*/ Vérifiez si l'utilisateur est déjà connecté
if (isset($_SESSION['email']) && $_SESSION['role'] === 'admin') {
    // Redirigez vers la page de gestion des utilisateurs si déjà connecté
    header("Location: admin_dashboard.php");
    exit();
}**/

// Vérifiez si l'administrateur est déjà inscrit
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE role = 'admin'");
$stmt->execute();
$admin = $stmt->fetch();

if (!$admin) {
    // Vérifiez si le formulaire d'inscription a été soumis
    if (isset($_POST['action']) && $_POST['action'] === 'inscription') {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];
        $confirmation_mot_de_passe = $_POST['confirmation_mot_de_passe'];

        // Vérifiez si les mots de passe correspondent
        if ($mot_de_passe === $confirmation_mot_de_passe) {
            // Hachage du mot de passe
            $mot_de_passe_hashé = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            // Préparer et exécuter la requête d'insertion
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (:nom, :email, :mot_de_passe, 'admin')");
            
            if ($stmt->execute([':nom' => $nom, ':email' => $email, ':mot_de_passe' => $mot_de_passe_hashé])) {
                $message = "Inscription réussie. Vous pouvez maintenant vous connecter.";
            } else {
                $message = "Erreur lors de l'inscription.";
            }
        } else {
            $message = "Les mots de passe ne correspondent pas.";
        }
    }
} else {
    // Vérifiez si le formulaire de connexion a été soumis
    if (isset($_POST['action']) && $_POST['action'] === 'connexion') {
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];

        // Préparer et exécuter la requête SQL
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email AND role = 'admin'");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        // Vérifiez si l'utilisateur existe
        if ($user) {
            // Vérifiez si le mot de passe est correct
            if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                // Authentification réussie, stockez les informations de session
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirigez vers la page de gestion des utilisateurs
                header("Location: AdminController.php");
                exit();
            } else {
                // Mot de passe incorrect
                $message = "Mot de passe incorrect.";
            }
        } else {
            // Aucun utilisateur trouvé avec cet email
            $message = "vous ne pouvez pas vous connecter avec cet email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription / Connexion Administrateur</title>
    <link rel="stylesheet" href="../assets/css/styleConnexion.css">
</head>
<body>
    <header>
        <h1>Inscription / Connexion Administrateur</h1>
    </header>

    <main>
        <section>
            <?php if (!$admin): ?>
                <h2>Inscription</h2>
                <form action="" method="POST">
                    <input type="hidden" name="action" value="inscription">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" required>
                    
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                    
                    <label for="mot_de_passe">Mot de passe :</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                    
                    <label for="confirmation_mot_de_passe">Confirmer le mot de passe :</label>
                    <input type="password" id="confirmation_mot_de_passe" name="confirmation_mot_de_passe" required>
                    
                    <button type="submit">S'inscrire</button>
                </form>
            <?php else: ?>
                <h2>Connexion</h2>
                <form action="" method="POST">
                    <input type="hidden" name="action" value="connexion">
                    <label for="email_connexion">Email :</label>
                    <input type="email" id="email_connexion" name="email" required>
                    
                    <label for="mot_de_passe_connexion">Mot de passe :</label>
                    <input type="password" id="mot_de_passe_connexion" name="mot_de_passe" required>
                    
                    <button type="submit">Se connecter</button>
                </form>
            <?php endif; ?>
            <div id="message" style="color: red;"><?php echo $message; ?></div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Universite Don Bosco de Lubumbashi. faire la différence.</p>
    </footer>
</body>
</html>