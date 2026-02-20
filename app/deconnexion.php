<?php
session_start();

$_SESSION = [];
session_destroy();


//$flash = null;

$_SESSION['flash'] = 'Vous avez été déconnecté(e).';

header('Location: connexion.php');
exit;



?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deconnexion</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <!-- Étape 16 — Afficher flash -->
    <?php if ($flash) : ?>
        <h1 class="flash"><?= htmlspecialchars($flash) ?></h1>
    <?php endif; ?>
</body>

</html>