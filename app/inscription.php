<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['prenom']) || empty($_POST['nom']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['c_password'])) {
        die('Tous les champs sont obligatoires');
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
        echo '<ul>';

        foreach ($erreurs as $erreur) {
            echo "<li>$erreur</li>";
        }

        echo '</ul>';
        die;

          
    }
    $passwordHash = password_hash($password, PASSWORD_ARGON2ID);
    require_once 'dbconnect.php';

    try{

        $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';
        
        $stmtCheck = $db->prepare($sql);

        $stmtCheck->bindValue(':email', $email, PDO::PARAM_STR);

        $stmtCheck->execute();

        if ($stmtCheck->fetchColumn() > 0) {
            $erreurs[] = 'Email déjà utilisé';
        } 
    
        $sql = 'INSERT INTO users(prenom, nom, email, password) VALUES (:prenom, :nom, :email, :password)';

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $passwordHash, PDO::PARAM_STR);

        $stmt->execute();

        $id = $db->lastInsertId();

        die("Adhérent ajouté avec l'ID n°$id");

    }catch(PDOException $exception){
        die('Une erreur est survenue : ' . $exception->getMessage());
    }
}



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
        <form class="form" action="" method="POST">
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
            <button type="submit">Envoyer</button>
        </form>
    </section>
</body>

</html>