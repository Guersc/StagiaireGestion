<?php
session_start();
include '../config.php';
require_once '../controllers/DemandeStageController.php';

$controller = new DemandeStageController($pdo);
$etudiant_id = $_SESSION['id'];

// Récupérer les demandes de stage
$demandes = $controller->afficherDemandes($etudiant_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entreprise_nom = $_POST['entreprise_nom'];
    $entreprise_lieu = $_POST['entreprise_lieu'];
    $entreprise_adresse = $_POST['entreprise_adresse'];
    $destinateur = $_POST['destinateur'];
    $confirmation_notice = isset($_POST['confirmation_notice']) ? 1 : 0; // 1 si coché, 0 sinon

    // Appel à la méthode du contrôleur pour ajouter la demande
    $controller->ajouterDemande($entreprise_nom, $entreprise_lieu, $entreprise_adresse, $destinateur, $confirmation_notice);
    header("Location: demandeStage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Stage</title>
    <link rel="stylesheet" href="../assets/css/styleEtudiant.css">
</head>
<body>
    <header>
        <h1>Demande de Stage</h1>
    </header>
    <main>
        <h2>Vos Demandes de Stage</h2>
        <ul>
            <?php foreach ($demandes as $demande): ?>
                <li><?php echo htmlspecialchars($demande['entreprise_nom']); ?> - <?php echo htmlspecialchars($demande['entreprise_lieu']); ?></li>
            <?php endforeach; ?>
        </ul>

        <h2>Faire une Nouvelle Demande</h2>
        <form action="demandeStage.php" method="POST">
            <label for="entreprise_nom">Nom de l'Entreprise :</label>
            <input type="text" id="entreprise_nom" name="entreprise_nom" required>

            <label for="entreprise_lieu">Lieu :</label>
            <input type="text" id="entreprise_lieu" name="entreprise_lieu" required>

            <label for="entreprise_adresse">Adresse :</label>
            <input type="text" id="entreprise_adresse" name="entreprise_adresse" required>

            <label for="destinateur">Destinateur :</label>
            <input type="text" id="destinateur" name="destinateur" value="Directeur des Ressources Humaines" readonly>

            <div>
                <input type="checkbox" id="confirmation_notice" name="confirmation_notice" required>
                <label for="confirmation_notice">J'accepte que ces informations sont sous ma responsabilité.</label>
            </div>

            <button type="submit">Soumettre la Demande</button>
            <a href="demandeStage.php" class="button">Accéder à la page de Demande de Stage</a>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 Université Don Bosco de Lubumbashi. Faire la différence.</p>
    </footer>
</body>
</html>