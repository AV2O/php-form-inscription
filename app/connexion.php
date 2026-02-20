<?php
session_start();

$flash = null;
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}

// ✅ $erreurs TOUJOURS déclarée
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['flash'] = 'Pas le bon formulaire';
        header('Location: connexion.php');
        exit;
    }
    unset($_SESSION['csrf_token']);

    $email = trim(strtolower($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    // ✅ TOUJOURS déclarée
    $erreurs = [];

    if (empty($email) || empty($password)) {
        $erreurs[] = "Tous les champs sont obligatoires";
    }

    if (!empty($erreurs)) {
        // Erreurs → affiche HTML
    } else {
        require_once 'dbconnect.php';
        
        try {
            $sql = 'SELECT prenom, nom, email, password FROM users WHERE email = :email';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user['password'])) {
                $erreurs[] = "Identifiants incorrects";
            }

            if (!empty($erreurs)) {
                // Erreurs → affiche HTML
            } else {
                session_regenerate_id(true);
                $_SESSION['utilisateur'] = [
                    'prenom' => $user['prenom'],
                    'nom' => $user['nom'],
                    'email' => $user['email']
                ];
                $_SESSION['CONNECTE'] = 'YES';
                $_SESSION['flash'] = 'Connexion réussie !';
                header('Location: profil.php');
                exit;
            }
        } catch (PDOException $exception) {
            $erreurs[] = 'Une erreur est survenue';
        }
    }
}

$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div>
        <?php if ($flash) : ?>
            <h1 class="flash ok"><?= htmlspecialchars($flash) ?></h1>
        <?php endif; ?>
    </div>
    <section>
        <h2>Connexion</h2>
        <form class="form" method="POST">
            <div>
                <label for="email">Email :</label>
                <input type="email" name="email" id="email">
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password">
            </div>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">
            <?php foreach($erreurs as $erreur) : ?>
                <p class="flash"><?= htmlspecialchars($erreur) ?></p>
            <?php endforeach; ?>
            <button type="submit">Se connecter</button>
            <p>Vous n'avez pas de compte ? <a href="inscription.php">Inscrivez-vous !</a></p>
        </form>
    </section>
</body>
</html>