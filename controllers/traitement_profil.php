<?php
session_start();
include '../config.php'; // Inclure le fichier de configuration pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}

// Récupérer les informations de l'étudiant
$etudiant_id = $_SESSION['id'];
$user_stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
$user_stmt->execute([':id' => $etudiant_id]);
$user = $user_stmt->fetch();

$etudiant_stmt = $pdo->prepare("SELECT * FROM Etudiants WHERE utilisateur_id = :id");
$etudiant_stmt->execute([':id' => $etudiant_id]);
$etudiant = $etudiant_stmt->fetch();

$incomplete = !$etudiant; // Vérifier si des informations manquent

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'modifier_etudiant') {
    $noms = $_POST['noms'];
    $telephone = $_POST['telephone'];
    $promotion = $_POST['promotion'];
    $filiere = $_POST['filiere'];
    $matricule = $_POST['matricule'];

    // Mettre à jour les informations de l'étudiant
    $stmt = $pdo->prepare("UPDATE Etudiants SET noms = :noms, telephone = :telephone, promotion = :promotion, filiere = :filiere, matricule = :matricule WHERE utilisateur_id = :utilisateur_id");
    $stmt->execute([
        ':utilisateur_id' => $etudiant_id,
        ':noms' => $noms,
        ':telephone' => $telephone,
        ':promotion' => $promotion,
        ':filiere' => $filiere,
        ':matricule' => $matricule,
    ]);

    header("Location: profil_etudiant.php"); // Rediriger vers le profil après la mise à jour
    exit();
}

// Affichage du formulaire
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'Étudiant</title>
    <link rel="stylesheet" href="../assets/css/style1.css">
</head>
<body>
    <header>
        <h1>Profil de l'Étudiant</h1>
    </header>

    <main>
        <section>
            <?php if ($incomplete): ?>
                <div style="color: red;">Veuillez compléter toutes les informations vous concernant.</div>
            <?php endif; ?>

            <h2>Informations de l'Étudiant</h2>
            <form action="traitement_profil.php" method="POST">
                <input type="hidden" name="action" value="modifier_etudiant">

                <label for="noms">Noms :</label>
                <input type="text" id="noms" name="noms" value="<?php echo htmlspecialchars($user['nom']); ?>" required readonly>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required readonly>

                <label for="telephone">Téléphone :</label>
                <input type="text" id="telephone" name="telephone" value="<?php echo htmlspecialchars($etudiant['telephone'] ?? ''); ?>" required>

                <label for="promotion">Promotion :</label>
                <select id="promotion" name="promotion" required>
                    <option value="" disabled <?php echo empty($etudiant['promotion']) ? 'selected' : ''; ?>>Sélectionnez une promotion</option>
                    <option value="L2" <?php echo ($etudiant['promotion'] == 'L2') ? 'selected' : ''; ?>>L2</option>
                    <option value="L3" <?php echo ($etudiant['promotion'] == 'L3') ? 'selected' : ''; ?>>L3</option>
                    <option value="L4" <?php echo ($etudiant['promotion'] == 'L4') ? 'selected' : ''; ?>>L4</option>
                    <option value="M1" <?php echo ($etudiant['promotion'] == 'M1') ? 'selected' : ''; ?>>M1</option>
                    <option value="M2" <?php echo ($etudiant['promotion'] == 'M2') ? 'selected' : ''; ?>>M2</option>
                </select>

                <label for="filiere">Filière :</label>
                <select id="filiere" name="filiere" required>
                    <option value="" disabled <?php echo empty($etudiant['filiere']) ? 'selected' : ''; ?>>Sélectionnez une filière</option>
                    <option value="Génie Logiciel" <?php echo ($etudiant['filiere'] == 'Génie Logiciel') ? 'selected' : ''; ?>>Génie Logiciel</option>
                    <option value="Gestion des Systèmes d'Informations" <?php echo ($etudiant['filiere'] == 'Gestion des Systèmes d\'Informations') ? 'selected' : ''; ?>>Gestion des Systèmes d'Informations</option>
                    <option value="Administration Système et Réseaux" <?php echo ($etudiant['filiere'] == 'Administration Système et Réseaux') ? 'selected' : ''; ?>>Administration Système et Réseaux</option>
                </select>

                <label for="matricule">Matricule :</label>
                <input type="text" id="matricule" name="matricule" value="<?php echo htmlspecialchars($etudiant['matricule'] ?? ''); ?>" required>

                <button type="submit">Modifier</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Université Don Bosco de Lubumbashi. Faire la différence.</p>
    </footer>
</body>
</html>