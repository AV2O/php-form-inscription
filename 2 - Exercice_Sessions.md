# Exercice — Inscription, CSRF, Messages flash & Connexion

## Contexte

Vous allez améliorer le formulaire d'inscription réalisé précédemment, puis créer une page de connexion complète avec gestion de session.

À la fin de cet exercice vous aurez :
- Un formulaire d'inscription sécurisé avec un token CSRF et des messages flash
- Une page de connexion qui démarre une session
- Une page profil protégée par la session
- Une déconnexion propre

**Fichiers concernés :**
- `inscription.php` — à modifier
- `connexion.php` — à créer
- `profil.php` — à créer
- `deconnexion.php` — à créer

---

## Partie 1 — Sécuriser l'inscription

### Étape 1 — Démarrer la session

`inscription.php` va maintenant utiliser les sessions pour le token CSRF et les messages flash.

Ajoutez `session_start()` tout en haut du fichier, avant toute autre instruction.

> À quelle condition `session_start()` fonctionne-t-il correctement ?

---

### Étape 2 — Générer le token CSRF

Le token doit être généré **avant** l'affichage du formulaire.

Dans la partie PHP en haut du fichier, en dehors du bloc `if POST`, générez un token aléatoire et stockez-le en session.

> Quelle fonction PHP permet de générer des octets aléatoires sécurisés, et comment les convertir en chaîne lisible ?

---

### Étape 3 — Ajouter le token dans le formulaire

Dans le formulaire HTML, ajoutez un champ caché qui transmet le token CSRF.

---

### Étape 4 — Vérifier le token à la soumission

Au tout début du bloc `if POST`, avant de vérifier les champs vides, comparez le token reçu dans `$_POST` avec celui stocké en session.

Si les tokens ne correspondent pas ou que le token est absent, affichez un message d'erreur et arrêtez l'exécution.

---

### Étape 5 — Remplacer les `die()` par des messages flash

Actuellement, chaque erreur ou succès utilise `die()` qui affiche du texte brut et bloque la page.

Remplacez chaque `die()` par un message flash suivi d'une redirection :

- Les erreurs de validation redirigent vers `inscription.php`
- Le succès de l'inscription redirige vers `connexion.php`

Pour les messages flash, stockez un tableau en session avec un `type` (`succes` ou `erreur`) et un `message`.

> Après avoir stocké le message flash, n'oubliez pas de supprimer le token CSRF de la session avant de rediriger — il a été consommé.

---

### Étape 6 — Afficher le message flash dans le formulaire

Dans la partie HTML de `inscription.php`, lisez le message flash s'il existe, affichez-le, puis supprimez-le immédiatement de la session.

> Pourquoi supprimer le message dès la lecture et pas après l'affichage HTML ?

---

## Partie 2 — La page de connexion

### Étape 7 — Créer `connexion.php`

Créez le fichier `connexion.php`. Comme pour `inscription.php`, ce sera un fichier unique qui gère à la fois l'affichage du formulaire et le traitement.

Démarrez la session en haut du fichier.

---

### Étape 8 — Afficher le message flash

Avant le formulaire, lisez et affichez le message flash s'il en existe un, puis supprimez-le de la session.

---

### Étape 9 — Construire le formulaire de connexion

Créez un formulaire HTML avec deux champs :
- Email
- Mot de passe

---

### Étape 10 — Détecter la soumission et vérifier les champs

Dans le bloc `if POST`, vérifiez que les deux champs sont renseignés.

Si un champ est vide, stockez un message flash d'erreur et redirigez vers `connexion.php`.

---

### Étape 11 — Récupérer l'utilisateur en base

Connectez-vous à la base de données et recherchez l'utilisateur dont l'email correspond à celui saisi.

> La requête doit être un `SELECT`. Que se passe-t-il si aucun utilisateur n'est trouvé ?

---

### Étape 12 — Vérifier le mot de passe

Si un utilisateur a été trouvé, vérifiez que le mot de passe saisi correspond au hash stocké en base.

> Quelle fonction PHP permet de vérifier un mot de passe contre son hash ?

Si l'utilisateur n'existe pas **ou** si le mot de passe est incorrect, affichez le même message d'erreur générique dans les deux cas.

> Pourquoi ne pas distinguer "email introuvable" et "mauvais mot de passe" dans le message affiché ?

---

### Étape 13 — Ouvrir la session

Si les identifiants sont corrects :

1. Régénérez l'identifiant de session
2. Stockez les informations nécessaires de l'utilisateur dans `$_SESSION`
3. Stockez un message flash de succès
4. Redirigez vers `profil.php`

> Quelles informations de l'utilisateur stocke-t-on en session ? Quelles informations ne doit-on jamais y mettre ?

---

## Partie 3 — La page profil

### Étape 14 — Créer `profil.php`

Créez le fichier `profil.php` et démarrez la session en haut du fichier.

---

### Étape 15 — Protéger la page

Vérifiez si l'utilisateur est connecté. S'il ne l'est pas, redirigez-le immédiatement vers `connexion.php`.

> Cette vérification doit se faire avant tout affichage HTML.

---

### Étape 16 — Afficher le message flash

Lisez et affichez le message flash s'il existe, puis supprimez-le.

---

### Étape 17 — Afficher les informations de l'utilisateur

Affichez un message de bienvenue personnalisé avec le prénom de l'utilisateur connecté, récupéré depuis la session.

---

### Étape 18 — Ajouter un lien de déconnexion

Ajoutez un lien vers `deconnexion.php`.

---

## Partie 4 — La déconnexion

### Étape 19 — Créer `deconnexion.php`

Créez le fichier `deconnexion.php`.

Démarrez la session, détruisez-la proprement, stockez un message flash de confirmation, puis redirigez vers `connexion.php`.

> Quelle est la bonne séquence pour détruire une session complètement ?

---

## Récapitulatif des fichiers

| Fichier | Rôle |
|---|---|
| `inscription.php` | Formulaire d'inscription avec CSRF et flash |
| `connexion.php` | Formulaire de connexion, ouverture de session |
| `profil.php` | Page protégée, accessible uniquement si connecté |
| `deconnexion.php` | Destruction de la session, redirection |

---

## Bonus — Tester les cas limites

- Soumettez le formulaire de connexion avec un email qui n'existe pas en base
- Soumettez avec le bon email mais un mauvais mot de passe
- Essayez d'accéder directement à `profil.php` sans être connecté
- Connectez-vous, puis accédez à `inscription.php` — que se passe-t-il ? Devrait-on rediriger un utilisateur déjà connecté ?
