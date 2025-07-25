<?php
require_once '../config.php'; // Assurez-vous que le chemin est correct
require_once "../controllers/EtudiantController.php";

// Instanciation du contrôleur
$controller = new EtudiantController($pdo);

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'etudiant') {
    try {
        // Appel à la méthode pour traiter l'insertion
        $controller->soumettreDemande($_POST);
        // Rediriger ou afficher un message de succès
        header('Location: success.php'); // Remplacez par votre page de succès
        exit;
    } catch (Exception $e) {
        // Gérer l'erreur (par exemple, afficher un message)
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Stage</title>
    <link rel="stylesheet" href="../assets/css/styleEtudiant.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <h2>Informations de l'Étudiant</h2>
    <!-- Ajout d'un champ caché pour le type de formulaire -->
    <form id="form-etudiant" method="POST" action="">
        <input type="hidden" name="form_type" value="etudiant">
        
        <label for="matricule">Matricule :</label>
        <input type="text" id="matricule" name="matricule" required>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="post_nom">Post-nom :</label>
        <input type="text" id="post_nom" name="post_nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="genre">Genre :</label>
        <select id="genre" name="genre" required>
            <option value="Masculin">Masculin</option>
            <option value="Féminin">Féminin</option>
        </select>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="promotion">Promotion :</label>
        <select id="promotion" name="promotion" required>
            <option value="">Sélectionnez une promotion</option>
            <option value="L1">L1</option>
            <option value="L2">L2</option>
            <option value="L3">L3</option>
            <option value="L4">L4</option>
            <option value="M1">M1</option>
            <option value="M2">M2</option>
        </select>

        <label for="filiere">Sélectionnez une filière :</label>
        <select id="filiere" name="filiere" required>
            <option value="genie logiciel">Génie Logiciel</option>
            <option value="gestion des systemes et reseaux">Gestion des Systèmes et Réseaux</option>
            <option value="telecommunications et reseaux">Télécommunications et Réseaux</option>
            <option value="design et multimedia">Design et Multimédia</option>
            <option value="reseaux et mobilite">Réseaux et Mobilité</option>
            <option value="MIAGE">MIAGE</option>
            <option value="communication numerique">Communication Numérique</option>
            <option value="data science">Data Science</option>
            <option value="science de base">Science de Base</option>
            <option value="autre">Autre</option>
        </select>

        <div id="other-field" style="display: none;">
            <label for="autre_filiere">Veuillez préciser :</label>
            <input type="text" id="autre_filiere" name="autre_filiere">
        </div>

        <label for="telephone">Téléphone :</label>
        <input type="text" id="telephone" name="telephone" required>

        <label for="mot_de_passe">Mot de Passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>

        <label for="confirmation_mot_de_passe">Confirmer le Mot de Passe :</label>
        <input type="password" id="confirmation_mot_de_passe" name="confirmation_mot_de_passe" required>

        <button type="submit">Suivant</button>
    </form>

    <?php if (isset($error_message)): ?>
        <div style="color: red;"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
</body>
</html>