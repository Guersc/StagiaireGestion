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

// Vérifier si l'étudiant a des stages enregistrés
$stages_stmt = $pdo->prepare("SELECT * FROM stages WHERE etudiant_id = :id");
$stages_stmt->execute([':id' => $_SESSION['id']]);
$stages = $stages_stmt->fetchAll();

// Récupérer le statut du stage
$stage_stmt = $pdo->prepare("SELECT statut FROM stages WHERE etudiant_id = :id");
$stage_stmt->execute([':id' => $_SESSION['id']]);
$stage = $stage_stmt->fetch();


// Vérifier si l'étudiant a des stages enregistrés
$stages_stmt = $pdo->prepare("SELECT * FROM stages WHERE etudiant_id = :id");
$stages_stmt->execute([':id' => $_SESSION['id']]);
$stages = $stages_stmt->fetchAll();

// Récupérer les tâches du carnet de stage
$carnet_stmt = $pdo->prepare("SELECT * FROM carnet_stage WHERE stage_id = :id ORDER BY date DESC");
$carnet_stmt->execute([':id' => $_SESSION['id']]);
$carnet_taches = $carnet_stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Stage - Gestion des Stagiaires</title>
    <link rel="stylesheet" href="../assets/css/styleStage.css">
</head>
<body>
    <header>
        <h1 style="display: inline;">Mon Stage</h1>
        <a href="../controllers/ProfilControlleur.php" class="profil-button">Profil
            <div class="notification" style="position: relative;">
                <span class="indicator" style="position: absolute; top: 0; right: 0; width: 10px; height: 10px; border-radius: 50%; background-color: green;"></span>
            </div>
        </a>
    </header>

    <main>
        <h2>Stages en Cours</h2>
        <p>Bienvenue dans la section "Mon Stage". Ici, vous pouvez visualiser et gérer les détails de votre stage.</p>

        
        <section>
            <h2>Carnet de Stage</h2>
            <form action="../controllers/traitement_carnet.php" method="POST">
                <input type="hidden" name="etudiant_id" value="<?php echo $_SESSION['id']; ?>">
                <label for="tache">Tâche journalière :</label>
                <textarea id="tache" name="tache" required></textarea>
                <button type="submit">Ajouter</button>
            </form>

            <h3>Tâches Enregistrées</h3>
            <ul>
                <?php foreach ($carnet_taches as $index => $tache): ?>
                    <li>
                        <?php echo htmlspecialchars($tache['date']); ?> - Tâche <?php echo $index + 1; ?> : <?php echo htmlspecialchars($tache['description']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section>
            <button onclick="window.location.href='stage_en_cours.php'" <?php echo empty($stages) ? 'disabled' : ''; ?>>Stage en cours</button>
            <button onclick="window.location.href='rapport.php'">Rapport de Stage</button>
        </section>

        <section>
            <h2>Démarrer un Stage</h2>
            <button onclick="document.getElementById('demarrerStage').style.display='block'">Démarrer votre stage</button>
           </section>

        <!-- Formulaire pour démarrer un stage -->
        <div id="demarrerStage" style="display:none;">
            <h3>Démarrer un Stage</h3>
            <form action="traitement_stage.php" method="POST">
                <input type="hidden" name="action" value="demarrer_stage">
                <h4>Informations sur l'entreprise</h4>
                <label for="nom_entreprise">Nom de l'entreprise :</label>
                <input type="text" id="nom_entreprise" name="nom_entreprise" required>

                <label for="lieu_stage">Lieu de stage :</label>
                <select id="lieu_stage" name="lieu_stage" required>
                    <option value="">Sélectionnez une ville</option>
                    <option value="Lubumbashi">Lubumbashi</option>
                    <option value="Kinshasa">Kinshasa</option>
                    <option value="Goma">Goma</option>
                    <option value="Bukavu">Bukavu</option>
                    <option value="Mbuji-Mayi">Mbuji-Mayi</option>
                </select>

                <label for="adresse_entreprise">Adresse complète :</label>
                <input type="text" id="adresse_entreprise" name="adresse_entreprise" required>

                <h4>Informations sur l'encadreur</h4>
                <label for="nom_encadreur">Nom de l'encadreur :</label>
                <input type="text" id="nom_encadreur" name="nom_encadreur" required>

                <label for="poste_encadreur">Poste dans l'entreprise :</label>
                <input type="text" id="poste_encadreur" name="poste_encadreur" required>

                <label for="email_encadreur">Email de l'encadreur :</label>
                <input type="email" id="email_encadreur" name="email_encadreur" required>

                <label for="telephone_encadreur">Numéro de téléphone :</label>
                <input type="text" id="telephone_encadreur" name="telephone_encadreur" required>

                <h4>Poste de stage</h4>
                <label for="poste_stage">Rôle durant le stage :</label>
                <input type="text" id="poste_stage" name="poste_stage" required>

                <button type="submit">Soumettre</button>
                <button type="button" onclick="document.getElementById('demarrerStage').style.display='none'">Annuler</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Université Don Bosco de Lubumbashi. Faire la différence.</p>
    </footer>
</body>
</html>