<?php
session_start();
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté et est un étudiant
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'étudiant') {
    header("Location: ../controllers/login.php"); // Rediriger vers la page de connexion
    exit();
}

// Récupérer l'ID de l'étudiant
$email = $_SESSION['email'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

// Vérifiez si l'utilisateur existe
if (!$user) {
    session_destroy();
    header("Location: ../controllers/login.php");
    exit();
}

// Récupérer les informations de stage si elles existent
$stmt = $pdo->prepare("SELECT * FROM stages WHERE etudiant_id = :id");
$stmt->execute([':id' => $user['id']]);
$stage = $stmt->fetch();

if (!$stage) {
    header("Location: mon_stage.php"); // Rediriger si aucun stage n'est trouvé
    exit();
}

// Traitement de l'arrêt du stage
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'arreter_stage') {
    // Supprimer l'enregistrement de stage
    $stmt = $pdo->prepare("DELETE FROM stages WHERE etudiant_id = :etudiant_id");
    $stmt->execute([':etudiant_id' => $user['id']]);
    header("Location: mon_stage.php"); // Rediriger vers la page de stage
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stage en Cours - Gestion des Stagiaires</title>
    <link rel="stylesheet" href="../assets/css/style1.css">
</head>
<body>
    <header>
        <h1>Stage en Cours</h1>
    </header>

    <main>
        <section>
            <h3>Détails de votre Stage</h3>
            <p><strong>Nom de l'entreprise :</strong> <?php echo htmlspecialchars($stage['nom_entreprise']); ?></p>
            <p><strong>Lieu de stage :</strong> <?php echo htmlspecialchars($stage['lieu_stage']); ?></p>
            <p><strong>Adresse complète :</strong> <?php echo htmlspecialchars($stage['adresse_entreprise']); ?></p>
            <p><strong>Nom de l'encadreur :</strong> <?php echo htmlspecialchars($stage['nom_encadreur']); ?></p>
            <p><strong>Poste de l'encadreur :</strong> <?php echo htmlspecialchars($stage['poste_encadreur']); ?></p>
            <p><strong>Email de l'encadreur :</strong> <?php echo htmlspecialchars($stage['email_encadreur']); ?></p>
            <p><strong>Téléphone de l'encadreur :</strong> <?php echo htmlspecialchars($stage['telephone_encadreur']); ?></p>
            <p><strong>Poste durant le stage :</strong> <?php echo htmlspecialchars($stage['poste_stage']); ?></p>
            
            <form action="stageCours.php" method="POST">
                <input type="hidden" name="action" value="arreter_stage">
                <button type="submit" style="background-color: red; color: white;">Arrêter votre stage</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Universite Don Bosco de Lubumbashi. faire la différence.</p>
    </footer>
</body>
</html>