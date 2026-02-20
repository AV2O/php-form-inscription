<?php
session_start();

// Étape 15 — Protéger : utilisateur connecté ?
// if (!isset($_SESSION['CONNECTE']) || $_SESSION['CONNECTE'] !== 'YES') {
//     header('Location: connexion.php');
//     exit;
// }

if (!isset($_SESSION['utilisateur'])) {
    header('Location: connexion.php');
    exit;
}

// Étape 16 — Lire flash et supprimer
$flash = null;

if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="page-profil">
    <!-- Étape 16 — Afficher flash -->
    <?php if ($flash) : ?>
        <h1 class="flash"><?= htmlspecialchars($flash) ?></h1>
    <?php endif; ?>
    <section>
        <!-- Étape 17 — Bienvenue prénom -->
        <?php if (isset($_SESSION['utilisateur']['prenom'])) : ?>
            <h2>Bienvenue <?= htmlspecialchars($_SESSION['utilisateur']['prenom']) ?> !</h2>
        <?php endif; ?>
        <img src="img/coucou1.jpg" alt="smiley coucou">

        <!-- Étape 18 — Lien déconnexion -->
        <button><a href="deconnexion.php" class="btn-deconnexion">Se déconnecter</a></button>
    </section>
</body>

</html>