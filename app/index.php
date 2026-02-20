<?php
session_start();

// Redirection si connecté
if (isset($_SESSION['CONNECTE']) && $_SESSION['CONNECTE'] === 'YES') {
    header('Location: profil.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <main>
        <section class="hero">
            <h1>Bienvenue !</h1>
            <p>Créez votre compte pour commencer</p>
            <div>
                <button><a href="inscription.php" class="btn-primary">S'inscrire</a></button>
                <button><a href="connexion.php">Déjà inscrit ?</a></button>
            </div>
        </section>
    </main>
</body>

</html>