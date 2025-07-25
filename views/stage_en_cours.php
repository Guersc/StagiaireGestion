<?php
session_start();
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: ../controllers/login.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}

// Vérifiez si l'ID de l'étudiant est défini
if (!isset($_SESSION['id'])) {
    header("Location: ../controllers/login.php"); // Rediriger si l'ID n'est pas défini
    exit();
}

// Récupérer les informations de stage
$stages_stmt = $pdo->prepare("SELECT * FROM stages WHERE etudiant_id = :id");
$stages_stmt->execute([':id' => $_SESSION['id']]);
$stages = $stages_stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stages en Cours</title>
    <link rel="stylesheet" href="../assets/css/styleStage.css">
</head>
<body>
    <header>
        <h1>Stages en Cours</h1>
        <a href="profil.php" class="profil-button">Profil</a>
    </header>

    <main>
        <section>
            <?php if (empty($stages)): ?>
                <p>Aucun stage en cours trouvé.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom de l'entreprise</th>
                            <th>Lieu de stage</th>
                            <th>Nom de l'encadreur</th>
                            <th>Email de l'encadreur</th>  <!-- Nouvelle colonne -->
                            <th>Poste dans l'entreprise</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stages as $stage): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($stage['nom_entreprise']); ?></td>
                                <td><?php echo htmlspecialchars($stage['lieu_stage']); ?></td>
                                <td><?php echo htmlspecialchars($stage['nom_encadreur']); ?></td>
                                <td><?php echo htmlspecialchars($stage['email_encadreur']); ?></td>  <!-- Affichage de l'email -->
                                <td><?php echo htmlspecialchars($stage['poste_encadreur']); ?></td>
                                <td>
                                    <form action="traitement_stage.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="modifier_stage">
                                        <input type="hidden" name="stage_id" value="<?php echo $stage['id']; ?>">
                                        <button type="submit">Modifier</button>
                                    </form>
                                    <form action="traitement_stage.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="arret_stage">
                                        <input type="hidden" name="stage_id" value="<?php echo $stage['id']; ?>">
                                        <button type="submit">Arrêter</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Université Don Bosco de Lubumbashi. Faire la différence.</p>
    </footer>
</body>
</html>