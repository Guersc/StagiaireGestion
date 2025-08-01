<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion des Stagiaires</title>
    <link rel="stylesheet" href="assets/css/style1.css">
</head>
<body>
    <header>
        <h1>Bienvenue sur STAGEnet </h1>
        <nav>
            <ul>
                <li><a href="controllers/login.php">Mon Stage</a></li>
                <li><a href="controllers/DemandeStageController.php">Lettre de Stage</a></li>
                <li><a href="entreprises_collaborateurs.php">Entreprises Collaboratrices</a></li>
                <li><a href="../controllers/OffreController.php">Offres de Stages</a></li>
                <li><a href="en_savoir_plus.php">En Savoir Plus</a></li>
                <li><a href="controllers/login.php">connexion</a></li> <!-- Lien pour déconnexion -->
                <li><a href="controllers/login_admin.php">administrateur</a></li><!-- connexion admin -->
            </ul>
        </nav>
    </header>

    <main>
        <section class="card">
            <h2><a href="controllers/DemandeStageController.php">Mon Stage</a></h2>
            <p>Visualisez et gérez les détails de votre stage.</p>
        </section>

        <section class="card">
            <h2><a href="views/etudiant_form.php">Lettre de Stage</a></h2>
            <p>Accédez aux modèles et aux informations concernant la lettre de stage.</p>
        </section>

        <section class="card">
            <h2><a href="entreprises_collaborateurs.php">Entreprises Collaboratrices</a></h2>
            <p>Consultez la liste des entreprises et des collaborateurs disponibles.</p>
        </section>

        <section class="card">
            <h2><a href="offres_stages.php">Offres de Stages</a></h2>
            <p>Découvrez les offres de stages disponibles pour les étudiants.</p>
        </section>

        <section class="card">
            <h2><a href="en_savoir_plus.php">En Savoir Plus</a></h2>
            <p>Renseignez-vous sur le processus de stage et les ressources disponibles.</p>
        </section>
    </main>

    
    <footer>
        <p>&copy; 2025 Universite Don Bosco de Lubumbashi. faire la difference.</p>
    </footer>
</body>
</html>