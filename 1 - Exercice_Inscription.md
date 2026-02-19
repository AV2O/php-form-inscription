# Exercice — Formulaire d'inscription

## Contexte

Vous allez créer une page d'inscription en PHP qui permet d'ajouter un utilisateur en base de données.

Vous travaillerez dans **un seul fichier** `inscription.php`, comme vous l'avez déjà fait.

---

## Étape 1 — Créer la table `users`

Exécutez ce SQL dans votre base de données pour créer la table :

```sql
CREATE TABLE users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email       VARCHAR(255) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    nom         VARCHAR(100) NOT NULL,
    prenom      VARCHAR(100) NOT NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

> **À noter :** `created_at` et `updated_at` sont gérés **automatiquement par MySQL**.
> Vous n'aurez pas à les renseigner dans votre requête PHP, MySQL s'en charge.

---

## Étape 2 — Créer le fichier `inscription.php`

Créez un fichier `inscription.php` à la racine de votre projet.

---

## Étape 3 — Construire le formulaire HTML

En bas de votre fichier, construisez une page HTML complète avec un formulaire contenant les champs suivants :

- Prénom
- Nom
- Email
- Mot de passe
- Confirmation du mot de passe

Chaque champ doit avoir un `name` cohérent avec ce que vous allez récupérer en PHP.
Le formulaire doit envoyer les données en méthode `POST`.

> Quel type d'input utilise-t-on pour un champ mot de passe afin de masquer la saisie ?

---

## Étape 4 — Détecter la soumission du formulaire

En haut du fichier, avant le HTML, ajoutez le bloc PHP qui vérifie si le formulaire a été soumis.

> Comment détecter qu'une requête est de type POST en PHP ?

---

## Étape 5 — Vérifier que les champs ne sont pas vides

À l'intérieur du bloc de soumission, vérifiez que les cinq champs sont bien renseignés.

Si au moins un champ est vide, affichez un message d'erreur et arrêtez l'exécution.

---

## Étape 6 — Récupérer et assainir les données

Récupérez les cinq valeurs depuis `$_POST` et stockez-les dans des variables.

> Pour le prénom et le nom, pensez à vous protéger contre les injections HTML.
> Pour l'email, le mot de passe et la confirmation, ne modifiez pas les valeurs, vous les validerez à l'étape suivante.

---

## Étape 7 — Valider les données

Initialisez un tableau `$erreurs` vide, puis ajoutez des contrôles :

- Le prénom ne doit pas dépasser 100 caractères
- Le nom ne doit pas dépasser 100 caractères
- L'email doit être une adresse valide
- Le mot de passe doit faire au minimum 8 caractères
- Le mot de passe et la confirmation doivent être identiques

> Quelle fonction PHP permet de valider le format d'un email ?

---

## Étape 8 — Afficher les erreurs si nécessaire

Si le tableau `$erreurs` contient au moins une erreur, affichez-les toutes et arrêtez l'exécution.

---

## Étape 9 — Se connecter à la base de données

Si on arrive ici, le formulaire est valide. Incluez le fichier de connexion à la base de données.

---

## Étape 10 — Insérer l'utilisateur en base

Dans un bloc `try/catch`, écrivez une requête `INSERT` pour insérer le nouvel utilisateur dans la table `users`.

Rappel de la marche à suivre :
1. Écrire la requête SQL avec des marqueurs nommés
2. Préparer la requête
3. Relier chaque valeur à son marqueur
4. Exécuter la requête

> Quels champs devez-vous renseigner ? Pensez à ce que MySQL gère automatiquement.

> ⚠️ **On ne stocke jamais un mot de passe en clair en base de données.**
> PHP fournit la fonction `password_hash()` qui chiffre le mot de passe avant de l'insérer.
> C'est la valeur retournée par cette fonction que vous devez stocker, pas le mot de passe saisi.

---

## Étape 11 — Confirmer l'inscription

Après l'insertion, récupérez l'identifiant du nouvel utilisateur et affichez un message de confirmation à l'écran.

---

## Étape 12 — Gérer les erreurs base de données

Dans le `catch`, affichez un message d'erreur si quelque chose se passe mal côté base de données.

---

## Bonus — Email déjà utilisé

La colonne `email` est déclarée `UNIQUE` en base.

Si un utilisateur essaie de s'inscrire avec un email déjà existant, MySQL va retourner une erreur.

- Testez ce comportement en insérant deux fois le même email.
- Que se passe-t-il ?
- Comment pourriez-vous afficher un message d'erreur clair à l'utilisateur plutôt que le message brut de MySQL ?
