<?php
session_start();
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: ../controllers/login.php");
    exit();
}

// Récupérer les stages en cours
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
    </header>

    <main>
        <section>
            <table>
                <thead>
                    <tr>
                        <th>Nom de l'entreprise</th>
                        <th>Lieu de stage</th>
                        <th>Nom de l'encadreur</th>
                        <th>Email de l'encadreur</th>
                        <th>Poste dans l'entreprise</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stages as $stage): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($stage['nom_entreprise']); ?></td>
                        <td><?php echo htmlspecialchars($stage['lieu_stage']); ?></td>
                        <td><?php echo htmlspecialchars($stage['nom_encadreur']); ?></td>
                        <td><?php echo htmlspecialchars($stage['email_encadreur']); ?></td>
                        <td><?php echo htmlspecialchars($stage['poste_encadreur']); ?></td>
                        <td><?php echo htmlspecialchars($stage['statut']); ?></td>
                        <td>
                            <form action="traitement_stage.php" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="accepter_stage">
                                <input type="hidden" name="stage_id" value="<?php echo $stage['id']; ?>">
                                <button type="submit">Accepter</button>
                            </form>
                            <form action="traitement_stage.php" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="refuser_stage">
                                <input type="hidden" name="stage_id" value="<?php echo $stage['id']; ?>">
                                <button type="submit">Refuser</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Université Don Bosco de Lubumbashi. Faire la différence.</p>
    </footer>
</body>
</html>