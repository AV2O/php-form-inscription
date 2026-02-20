<?php
session_start(); // Étape 1 — Démarrer la session (avant tout output)

$flash = null; // Étape 6 — Variable pour message flash

// Étape 6 — Lire le message flash et supprimer immédiatement
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Étape 4 — Vérifier le token CSRF en premier
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['flash'] = 'Pas le bon formulaire'; // Étape 5 — Flash + redirect
        header('Location: inscription.php');
        exit;
    }

    // Étape 5 — Remplacer die() par flash + redirect
    if (empty($_POST['prenom']) || empty($_POST['nom']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['c_password'])) {
        $_SESSION['flash'] = 'Tous les champs sont obligatoires'; // Étape 5
        header('Location: inscription.php');
        exit;
    }

    $prenom = htmlspecialchars($_POST['prenom']);
    $nom = htmlspecialchars($_POST['nom']);
    $email = trim(strtolower($_POST['email']));

    $password = $_POST['password'];
    $c_password = $_POST['c_password'];

    $erreurs = [];

    if (strlen($prenom) > 100) {
        $erreurs[] = 'Le prénom est trop long';
    }

    if (strlen($nom) > 100) {
        $erreurs[] = 'Le nom est trop long';
    }

    if (strlen($email) > 320) {
        $erreurs[] = 'La longueur de l\'adresse email est invalide';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'L\'adresse email est invalide';
    }

    if ($password !== $c_password) {
        $erreurs[] = 'Les mots de passe ne correspondent pas';
    } elseif (strlen($password) < 8) {
        $erreurs[] = 'Le mot de passe doit faire au moins 8 caractères';
    }

    if ($erreurs) {
        $_SESSION['flash'] = implode('<br>', $erreurs); // Étape 5 — Flash unique
        header('Location: inscription.php');
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_ARGON2ID);
    require_once 'dbconnect.php';

    try {

        $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';

        $stmtCheck = $db->prepare($sql);

        $stmtCheck->bindValue(':email', $email, PDO::PARAM_STR);

        $stmtCheck->execute();

        if ($stmtCheck->fetchColumn() > 0) {
            $erreurs[] = 'Email déjà utilisé';
            // Étape 5 — Remplacer/ajouter flash + redirect
            $_SESSION['flash'] = 'Email déjà utilisé';
            header('Location: inscription.php');
            exit;
        }

        $sql = 'INSERT INTO users(prenom, nom, email, password) VALUES (:prenom, :nom, :email, :password)';

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $passwordHash, PDO::PARAM_STR);

        $stmt->execute();

        $id = $db->lastInsertId();


        // Étape 5 — Remplacer die() par flash + redirect vers connexion.php
        $_SESSION['flash'] = "Bienvenue, Veuillez vous connecter !";
        header('Location: connexion.php');
        exit;
    } catch (PDOException $exception) {
        // Étape 5 — Remplacer die()
        $_SESSION['flash'] = 'Une erreur est survenue : ' . $exception->getMessage();
        header('Location: inscription.php');
        exit;
    }
}

// Étape 2 — Générer token CSRF avant formulaire
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercice — Formulaire d'inscription</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <h1>Formulaire d'inscription</h1>
    <section>
        <h2>Créez votre compte</h2>
        <!-- Étape 3 — Formulaire avec token CSRF -->
        <form class="form" method="POST">
            <input
                type="hidden"
                name="csrf_token"
                value="<?php echo htmlspecialchars($token); ?>" />
            <div>
                <label for="prenom">Prénom :</label>
                <input type="text" name="prenom" id="prenom">
            </div>
            <div>
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom">
            </div>
            <div>
                <label for="email">Email :</label>
                <input type="email" name="email" id="email">
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <label for="c_password">Confirmation de mot de passe :</label>
                <input type="password" name="c_password" id="c_password">
            </div>
            <?php if ($flash) : ?>
                <p class="flash show"><?= htmlspecialchars($flash) ?></p>
            <?php endif; ?>
            <button type="submit">Envoyer</button>
            <p>Vous avez déja un compte ? <a href="index.php">Accueil</a></p>
        </form>
    </section>
</body>

</html>