<?php
// =================================================================
// ÉBAUCHE DE LOGIQUE PHP
// =================================================================

// 1. Connexion à la base de données (Responsabilité de l'Hébergeur)
// include 'connect.php'; 
// Assurez-vous que le fichier connect.php contient la création de votre objet $pdo de connexion.

$message_soumission = '';
$message_vote = '';
$dossier_uploads = 'uploads/'; // Assurez-vous que ce dossier existe et est accessible en écriture !

// =================================================================
// TRAITEMENT DU FORMULAIRE DE SOUMISSION DE PHOTO
// =================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_photo'])) {
    
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email'] ?? '')); // Champs optionnel
    
    // Vérification de l'envoi de fichier
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        
        // Sécurité : Validation des types de fichiers (JPEG/PNG)
        $allowed_types = ['image/jpeg', 'image/png'];
        if (!in_array($_FILES['photo']['type'], $allowed_types)) {
            $message_soumission = "<p style='color: red;'>Erreur: Seuls les fichiers JPEG et PNG sont acceptés.</p>";
        } else {
            // Création d'un nom de fichier unique
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $nom_fichier_unique = uniqid('photo_') . '.' . $extension;
            $chemin_final = $dossier_uploads . $nom_fichier_unique;

            // Déplacement du fichier temporaire vers le dossier permanent
            // C'est l'étape où PHP enregistre la photo sur le serveur.
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $chemin_final)) {
                
                // Étape BDD : Enregistrement du chemin et des données auteur
                /*
                if (isset($pdo)) {
                    $stmt = $pdo->prepare("INSERT INTO submissions (nom, prenom, email, chemin_photo) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$nom, $prenom, $email, $chemin_final]);
                    $message_soumission = "<p style='color: green;'>Félicitations $prenom, votre photo a été soumise et enregistrée !</p>";
                } else {
                    $message_soumission = "<p style='color: orange;'>Photo uploadée, mais connexion BDD non établie. (Vérifiez connect.php)</p>";
                }
                */
                $message_soumission = "<p style='color: green;'>Félicitations $prenom, votre photo a été soumise avec succès!</p>";

            } else {
                $message_soumission = "<p style='color: red;'>Erreur lors du déplacement du fichier vers le dossier 'uploads/'. (Vérifiez les permissions)</p>";
            }
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_photo'])) {
        $message_soumission = "<p style='color: red;'>Veuillez sélectionner un fichier photo valide.</p>";
    }
}

// =================================================================
// TRAITEMENT DU FORMULAIRE DE VOTE
// =================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_vote'])) {
    if (isset($_POST['vote_id']) && is_numeric($_POST['vote_id'])) {
        $photo_id = (int)$_POST['vote_id'];
        
        // Étape BDD : Mise à jour du nombre de votes
        /*
        if (isset($pdo)) {
            $stmt = $pdo->prepare("UPDATE submissions SET votes = votes + 1 WHERE id = ?");
            $stmt->execute([$photo_id]);
            $message_vote = "<p style='color: green;'>Votre vote pour la photo #$photo_id a bien été enregistré!</p>";
        } else {
            $message_vote = "<p style='color: orange;'>Vote enregistré, mais connexion BDD non établie. (Vérifiez connect.php)</p>";
        }
        */
        $message_vote = "<p style='color: green;'>Votre vote pour la photo #$photo_id a bien été enregistré! (Logique BDD à finaliser)</p>";

    } else {
        $message_vote = "<p style='color: red;'>Veuillez sélectionner une photo pour voter.</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concours Photo - Soumission et Vote</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <header class="header-classe-1">
        <h1>Concours Photo Ornithologique 📸</h1>
        <p>
            <a href="index.html" class="lien-classe-2">← Retour à la Page d'Accueil</a>
        </p>
    </header>

    <main>
        
        <section class="section-classe-3" id="soumission-photo">
            <h2>Soumettre votre Photo d'Oiseau</h2>
            
            <?php echo $message_soumission; // Afficher le résultat de la soumission ?>
            
            <p>
                **Règles du concours (À inventer) :** La photo doit illustrer un oiseau dans l'Espace Naturel de la Motte, être soumise en JPG ou PNG (max. 5 Mo). Un seul cliché par participant est autorisé. Le non-respect des règles entraînera l'annulation de la participation.
            </p>
            
            <form action="concours_photo.php" method="post" enctype="multipart/form-data">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required><br><br>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required><br><br>
                
                <label for="email">Email (Optionnel) :</label>
                <input type="email" id="email" name="email"><br><br>

                <label for="photo">Sélectionner votre Photo (.jpg ou .png) :</label>
                <input type="file" id="photo" name="photo" accept="image/jpeg, image/png" required><br><br>

                <button type="submit" name="submit_photo" class="bouton-classe-4">Envoyer ma photo</button>
            </form>
        </section>
        
        <hr>

        <section class="section-classe-3" id="vote-concours">
            <h2>Voter pour votre Photo Préférée</h2>

            <?php echo $message_vote; // Afficher le résultat du vote ?>

            <p>
                Sélectionnez la photo que vous souhaitez soutenir dans le concours et cliquez sur "Voter".
            </p>
            
            <form action="concours_photo.php" method="post">
                
                <p>
                    **APERÇU DES PHOTOS À VOTER (PLACEHOLDERS)**
                </p>
                
                <div>
                    <input type="radio" id="vote-1" name="vote_id" value="1" required>
                    <label for="vote-1">Photo 1 : "Aigle Pêcheur" par M. Créa</label>
                </div>
                <div>
                    <input type="radio" id="vote-2" name="vote_id" value="2">
                    <label for="vote-2">Photo 2 : "Bécasse des bois" par Mme. B</label>
                </div>
                <br>
                <button type="submit" name="submit_vote" class="bouton-classe-4">Voter</button>
            </form>
        </section>

    </main>

    <footer>
        <p>&copy; 2025 MMI - R3.14 Déploiement de service</p>
    </footer>
    
</body>
</html>