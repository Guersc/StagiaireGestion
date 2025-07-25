<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'Étudiant</title>
    <link rel="stylesheet" href="../assets/css/styleProfil.css">
    <style>
        #resetPasswordForm {
            display: none; /* Cacher le formulaire par défaut */
        }
        #editForm {
            display: none; /* Cacher le formulaire de modification par défaut */
        }
    </style>
</head>
<body>
    <header>
        <h1>Profil de l'Étudiant</h1>
    </header>

    <main>
        <section>
            <?php if (isset($_GET['message'])): ?>
                <div style="color: green;"><?php echo htmlspecialchars($_GET['message']); ?></div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div style="color: red;"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <h2>Informations de l'Étudiant</h2>

            <div>
                <p><strong>Matricule :</strong> <span><?php echo htmlspecialchars($etudiant['matricule']); ?></span></p>
                <p><strong>Nom :</strong> <span><?php echo htmlspecialchars($etudiant['nom']); ?></span></p>
                <p><strong>Post-nom :</strong> <span><?php echo htmlspecialchars($etudiant['post_nom']); ?></span></p>
                <p><strong>Prénom :</strong> <span><?php echo htmlspecialchars($etudiant['prenom']); ?></span></p>
                <p><strong>Genre :</strong> <span><?php echo htmlspecialchars($etudiant['genre']); ?></span></p>
                <p><strong>Email :</strong> <span><?php echo htmlspecialchars($etudiant['email']); ?></span></p>
                <p><strong>Promotion :</strong> <span><?php echo htmlspecialchars($etudiant['promotion']); ?></span></p>
                <p><strong>Filière :</strong> <span><?php echo htmlspecialchars($etudiant['filiere']); ?></span></p>
                <p><strong>Téléphone :</strong> <span><?php echo htmlspecialchars($etudiant['telephone']); ?></span></p>
            </div>

            <button id="editButton">Modifier vos informations</button>
            <button id="resetPasswordButton">Réinitialiser votre Mot de Passe</button>

            <div id="editForm">
                <h2>Modifier vos Informations</h2>
                <form action="profil.php" method="POST">
                    <input type="hidden" name="action" value="modifier_etudiant">
                    
                    <label for="matricule">Matricule :</label>
                    <input type="text" id="matricule" name="matricule" value="<?php echo htmlspecialchars($etudiant['matricule']); ?>" required>

                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($etudiant['nom']); ?>" required>

                    <label for="post_nom">Post-nom :</label>
                    <input type="text" id="post_nom" name="post_nom" value="<?php echo htmlspecialchars($etudiant['post_nom']); ?>" required>

                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($etudiant['prenom']); ?>" required>

                    <label for="genre">Genre :</label>
                    <select id="genre" name="genre" required>
                        <option value="Masculin" <?php if ($etudiant['genre'] == 'Masculin') echo 'selected'; ?>>Masculin</option>
                        <option value="Féminin" <?php if ($etudiant['genre'] == 'Féminin') echo 'selected'; ?>>Féminin</option>
                    </select>

                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($etudiant['email']); ?>" required>

                    <label for="promotion">Promotion :</label>
                    <input type="text" id="promotion" name="promotion" value="<?php echo htmlspecialchars($etudiant['promotion']); ?>" required>

                    <label for="filiere">Filière :</label>
                    <input type="text" id="filiere" name="filiere" value="<?php echo htmlspecialchars($etudiant['filiere']); ?>" required>

                    <label for="telephone">Téléphone :</label>
                    <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($etudiant['telephone']); ?>" required>

                    <button type="submit">Sauvegarder les modifications</button>
                    <button type="button" id="cancelEditButton">Annuler</button>
                </form>
            </div>

            <div id="resetPasswordForm">
                <h2>Réinitialiser votre Mot de Passe</h2>
                <form action="profil.php" method="POST">
                    <input type="hidden" name="action" value="reset_password">
                    <label for="ancien_mot_de_passe">Ancien Mot de Passe :</label>
                    <input type="password" id="ancien_mot_de_passe" name="ancien_mot_de_passe" required>
                    <label for="nouveau_mot_de_passe">Nouveau Mot de Passe :</label>
                    <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required>
                    <button type="submit">Réinitialiser</button>
                    <button type="button" id="cancelResetButton">Annuler</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Université Don Bosco de Lubumbashi. Faire la différence.</p>
    </footer>

    <script>
        document.getElementById('editButton').onclick = function() {
            document.getElementById('editForm').style.display = 'block';
        };

        document.getElementById('resetPasswordButton').onclick = function() {
            var resetForm = document.getElementById('resetPasswordForm');
            resetForm.style.display = (resetForm.style.display === 'none') ? 'block' : 'none';
        };

        document.getElementById('cancelEditButton').onclick = function() {
            document.getElementById('editForm').style.display = 'none';
        };

        document.getElementById('cancelResetButton').onclick = function() {
            document.getElementById('resetPasswordForm').style.display = 'none';
        };
    </script>
</body>
</html>