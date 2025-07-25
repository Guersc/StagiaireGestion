<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Stage</title>
    <link rel="stylesheet" href="../assets/css/styleStage.css">
</head>
<body>
    <header>
        <h1>Soumettre un Rapport de Stage</h1>
    </header>
    
    <main>
        <form action="../controllers/traitement_rapport.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="etudiant_id" value="<?php echo htmlspecialchars($_SESSION['id']); ?>">
            <label for="rapport">Choisissez un fichier PDF :</label>
            <input type="file" id="rapport" name="rapport" accept=".pdf" required>
            <button type="submit">Soumettre</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Université Don Bosco de Lubumbashi. Faire la différence.</p>
    </footer>
</body>
</html>