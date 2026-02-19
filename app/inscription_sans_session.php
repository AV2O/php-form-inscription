<?php
// On initialise les variables d'affichage
$erreurs = [];
$succes  = null;

// Vérifier que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // On vérifie si tous les champs obligatoires sont présents
    if (
        empty($_POST['prenom']) ||
        empty($_POST['nom']) ||
        empty($_POST['email']) ||
        empty($_POST['password']) ||
        empty($_POST['confirm_password'])
    ) {
        $erreurs[] = 'Tous les champs sont obligatoires.';
    } else {

        // On récupère les valeurs depuis $_POST
        $prenom           = htmlspecialchars($_POST['prenom']);
        $nom              = htmlspecialchars($_POST['nom']);
        $email            = $_POST['email'];
        $password         = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Le prénom ne doit pas dépasser 100 caractères
        if (strlen($prenom) > 100) {
            $erreurs[] = 'Le prénom est trop long.';
        }

        // Le nom ne doit pas dépasser 100 caractères
        if (strlen($nom) > 100) {
            $erreurs[] = 'Le nom est trop long.';
        }

        // L'email doit être valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = 'L\'adresse email est invalide.';
        }

        // Le mot de passe doit faire au minimum 8 caractères
        if (strlen($password) < 8) {
            $erreurs[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        // Le mot de passe et la confirmation doivent être identiques
        if ($password !== $confirm_password) {
            $erreurs[] = 'Les mots de passe ne correspondent pas.';
        }

        // S'il n'y a pas d'erreurs, on insère en base
        if (empty($erreurs)) {

            // On hache le mot de passe avant de le stocker
            $password_hash = password_hash($password, PASSWORD_ARGON2ID);

            // On se connecte à la base de données
            require_once 'dbconnect.php';

            try {
                $sql = 'INSERT INTO users (prenom, nom, email, password) VALUES (:prenom, :nom, :email, :password)';

                $stmt = $db->prepare($sql);

                $stmt->bindValue(':prenom',   $prenom,        PDO::PARAM_STR);
                $stmt->bindValue(':nom',      $nom,           PDO::PARAM_STR);
                $stmt->bindValue(':email',    $email,         PDO::PARAM_STR);
                $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);

                $stmt->execute();

                $succes = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';

            } catch (PDOException $exception) {
                // Email déjà utilisé (contrainte UNIQUE)
                if ($exception->getCode() === '23000') {
                    $erreurs[] = 'Cette adresse email est déjà utilisée.';
                } else {
                    $erreurs[] = 'Une erreur est survenue, veuillez réessayer.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Créer un compte</h1>

    <?php if ($succes) : ?>
        <p class="succes">
            <?php echo htmlspecialchars($succes); ?>
        </p>
    <?php endif; ?>

    <?php foreach ($erreurs as $erreur) : ?>
        <p class="erreur">
            <?php echo htmlspecialchars($erreur); ?>
        </p>
    <?php endforeach; ?>

    <form method="post">
        <div>
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom">
        </div>
        <div>
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom">
        </div>
        <div>
            <label for="email">Email</label>
            <input type="text" name="email" id="email">
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" id="confirm_password">
        </div>
        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
