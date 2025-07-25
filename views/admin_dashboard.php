<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administrateur</title>
    <link rel="stylesheet" href="../assets/css/styleAdmin.css">
    <script>
        function showSection(section) {
            // Cacher toutes les sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(s => s.style.display = 'none');

            // Afficher la section sélectionnée
            document.getElementById(section).style.display = 'block';
        }

        // Afficher la section par défaut au chargement
        window.onload = function() {
            showSection('dashboardSection'); // Section à afficher par défaut
        }
    </script>
</head>
<body>
    <header>
        <h1>Admin Manager</h1>
    </header>

    <nav>
        <ul>
            <li><a href="javascript:void(0);" onclick="showSection('dashboardSection')">dashboard</a></li>
            <li><a href="javascript:void(0);" onclick="showSection('offresSection')">Offres de stage</a></li>
            <li><a href="javascript:void(0);" onclick="showSection('lettreSection')">demandes de lettres </a></li>
            <li><a href="javascript:void(0);" onclick="showSection('etudiantsSection')">Etudiants</a></li>
            <li><a href="javascript:void(0);" onclick="showSection('statistiquesSection')">Statistiques</a></li>
            <li><a href="javascript:void(0);" onclick="showSection('parametresSection')">Paramètres</a></li>
        </ul>
    </nav>

    <main>
        <!-- Section Tableau de Bord -->
        <section id="dashboardSection" class="content-section" style="display: none;">
            <h2>Tableau de Bord</h2>
            <p>Bienvenue dans le tableau de bord de l'administrateur.</p>
        </section>

       <section id="lettreSection" class="content-section" style="display: none;">
    <h2>Demandes de Stages</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Étudiant</th>
                <th>Nom de l'Entreprise</th>
                <th>Lieu</th>
                <th>Adresse</th>
                <th>Destinataire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($demandes) && !empty($demandes)) {
                foreach ($demandes as $demande) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($demande['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($demande['Etudiant_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($demande['entreprise_nom']) . "</td>";
                    echo "<td>" . htmlspecialchars($demande['entreprise_lieu']) . "</td>";
                    echo "<td>" . htmlspecialchars($demande['entreprise_adresse']) . "</td>";
                    echo "<td>" . htmlspecialchars($demande['destinateur']) . "</td>";
                    echo "<td>
                            <div class='button-container'>
                                <form method='POST' action='supprimer_demande_stage.php'>
                                    <input type='hidden' name='id' value='" . $demande['id'] . "'>
                                    <button type='submit' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cette demande ?\");'>Supprimer</button>
                                </form>
                            </div>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Aucune demande de stage enregistrée.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

        <!-- Section Offres -->
        <section id="offresSection" class="content-section" style="display: none;">
            <h2>Créer une Offre de Stage</h2>
            <form method="POST">
                <label>Nom de l'Entreprise :</label>
                <input type="text" name="entreprise_nom" required>
                
                <label>Lieu :</label>
                <input type="text" name="entreprise_lieu" required>
                
                <label>Adresse :</label>
                <input type="text" name="entreprise_adresse" required>
                
                <label>Description :</label>
                <textarea name="description" required></textarea>
                
                <label>Date Limite :</label>
                <input type="date" name="date_limite" required>
                
                <button type="submit">Créer l'Offre</button>
            </form>
            <div><?php echo $message; ?></div>

            <h2>Offres de Stage</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom de l'Entreprise</th>
                        <th>Lieu</th>
                        <th>Adresse</th>
                        <th>Description</th>
                        <th>Date Limite</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Récupérer les offres de stage depuis la base de données
                    // Assurez-vous d'avoir une connexion à la base de données
                    $query = "SELECT * FROM offres_stage";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute();
                    $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($offres as $offre) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($offre['entreprise_nom']) . "</td>";
                        echo "<td>" . htmlspecialchars($offre['entreprise_lieu']) . "</td>";
                        echo "<td>" . htmlspecialchars($offre['entreprise_adresse']) . "</td>";
                        echo "<td>" . htmlspecialchars($offre['description']) . "</td>";
                        echo "<td>" . htmlspecialchars($offre['date_limite']) . "</td>";
                        echo "<td>
                                <div class='button-container'>
                                    <form method='POST' action='modifier_offre.php'>
                                        <input type='hidden' name='id' value='" . $offre['id'] . "'>
                                        <button type='submit' name='edit_offer'>Modifier</button>
                                    </form>
                                    <form method='POST' action='supprimer_offre.php'>
                                        <input type='hidden' name='id' value='" . $offre['id'] . "'>
                                        <button type='submit' name='delete_offer' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cette offre ?\");'>Supprimer</button>
                                    </form>
                                </div>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Section Étudiants -->
        <section id="etudiantsSection" class="content-section" style="display: none;">
            <h2>Liste des Étudiants</h2>
            <form action="admin_dashboard.php" method="POST">
                <input type="text" name="search_term" placeholder="Rechercher par nom ou email" required>
                <button type="submit" name="search">Rechercher</button>
            </form>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Post Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($etudiants as $etudiant): ?>
                <tr>
                    <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['post_nom']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['email']); ?></td>
                    <td>
                        <button onclick="document.getElementById('editForm<?php echo $etudiant['id']; ?>').style.display='block'">Modifier</button>
                        <div id="editForm<?php echo $etudiant['id']; ?>" style="display:none;">
                            <form action="admin_dashboard.php" method="POST">
                                <input type="hidden" name="student_id" value="<?php echo $etudiant['id']; ?>">
                                <label for="edit_nom">Nom :</label>
                                <input type="text" name="edit_nom" value="<?php echo htmlspecialchars($etudiant['nom']); ?>" required>
                                
                                <label for="edit_post_nom">Post Nom :</label>
                                <input type="text" name="edit_post_nom" value="<?php echo htmlspecialchars($etudiant['post_nom']); ?>" required>

                                <label for="edit_prenom">Prénom :</label>
                                <input type="text" name="edit_prenom" value="<?php echo htmlspecialchars($etudiant['prenom']); ?>" required>

                                <label for="edit_genre">Genre :</label>
                                <select name="edit_genre" required>
                                    <option value="Masculin" <?php echo $etudiant['genre'] == 'Masculin' ? 'selected' : ''; ?>>Masculin</option>
                                    <option value="Féminin" <?php echo $etudiant['genre'] == 'Féminin' ? 'selected' : ''; ?>>Féminin</option>
                                </select>

                                <label for="edit_email">Email :</label>
                                <input type="email" name="edit_email" value="<?php echo htmlspecialchars($etudiant['email']); ?>" required>
                                
                                <label for="edit_promotion">Promotion :</label>
                                <input type="text" name="edit_promotion" value="<?php echo htmlspecialchars($etudiant['promotion']); ?>" required>

                                <label for="edit_filiere">Filière :</label>
                                <input type="text" name="edit_filiere" value="<?php echo htmlspecialchars($etudiant['filiere']); ?>" required>

                                <label for="edit_telephone">Téléphone :</label>
                                <input type="text" name="edit_telephone" value="<?php echo htmlspecialchars($etudiant['telephone']); ?>" required>

                                <button type="submit" name="edit_student">Modifier</button>
                                <button type="button" onclick="document.getElementById('editForm<?php echo $etudiant['id']; ?>').style.display='none'">Annuler</button>
                            </form>
                        </div>
                        <a href="admin_dashboard.php?delete_id=<?php echo $etudiant['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

             <h2>Ajouter un Nouvel Étudiant</h2>
            <form action="admin_dashboard.php" method="POST">
                <label for="new_nom">Nom :</label>
                <input type="text" id="new_nom" name="new_nom" required>
                
                <label for="new_post_nom">Post Nom :</label>
                <input type="text" id="new_post_nom" name="new_post_nom" required>

                <label for="new_prenom">Prénom :</label>
                <input type="text" id="new_prenom" name="new_prenom" required>

                <label for="new_genre">Genre :</label>
                <select id="new_genre" name="new_genre" required>
                    <option value="Masculin">Masculin</option>
                    <option value="Féminin">Féminin</option>
                </select>
                
                <label for="new_email">Email :</label>
                <input type="email" id="new_email" name="new_email" required>
                
                <label for="new_mot_de_passe">Mot de passe :</label>
                <input type="password" id="new_mot_de_passe" name="new_mot_de_passe" required>
                
                <label for="new_promotion">Promotion :</label>
                <input type="text" id="new_promotion" name="new_promotion" required>

                <label for="new_filiere">Filière :</label>
                <input type="text" id="new_filiere" name="new_filiere" required>

                <label for="new_telephone">Téléphone :</label>
                <input type="text" id="new_telephone" name="new_telephone" required>
                
                <button type="submit" name="add_student">Ajouter</button>
            </form>
            <div style="color: red;"><?php echo $message ?? ''; ?></div>
        </section>

        <!-- Section Ajouter un Nouvel Étudiant -->
        <section id="ajouterEtudiantSection" class="content-section" style="display: none;">
            <h2>Ajouter un Nouvel Étudiant</h2>
            <form action="admin_dashboard.php" method="POST">
                <label for="new_nom">Nom :</label>
                <input type="text" id="new_nom" name="new_nom" required>
                
                <label for="new_post_nom">Post Nom :</label>
                <input type="text" id="new_post_nom" name="new_post_nom" required>

                <label for="new_prenom">Prénom :</label>
                <input type="text" id="new_prenom" name="new_prenom" required>

                <label for="new_genre">Genre :</label>
                <select id="new_genre" name="new_genre" required>
                    <option value="Masculin">Masculin</option>
                    <option value="Féminin">Féminin</option>
                </select>
                
                <label for="new_email">Email :</label>
                <input type="email" id="new_email" name="new_email" required>
                
                <label for="new_mot_de_passe">Mot de passe :</label>
                <input type="password" id="new_mot_de_passe" name="new_mot_de_passe" required>
                
                <label for="new_promotion">Promotion :</label>
                <input type="text" id="new_promotion" name="new_promotion" required>

                <label for="new_filiere">Filière :</label>
                <input type="text" id="new_filiere" name="new_filiere" required>

                <label for="new_telephone">Téléphone :</label>
                <input type="text" id="new_telephone" name="new_telephone" required>
                
                <button type="submit" name="add_student">Ajouter</button>
            </form>
            <div style="color: red;"><?php echo $message ?? ''; ?></div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Université Don Bosco de Lubumbashi. Faire la différence.</p>
    </footer>
</body>
</html>