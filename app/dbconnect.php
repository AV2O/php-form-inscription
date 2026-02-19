<?php
// On définit des constantes en fonction du projet (valeurs prises dans docker-compose.yaml)
const DBHOST = 'database'; // Serveur de base de données (nom du service Docker)
const DBNAME = 'exoPassWord'; // Nom de la base de données
const DBUSER = 'app'; // Nom d'utilisateur mysql
const DBPASS = 'azerty'; // Mot de passe de l'utilisateur app

// TOUTES LES LIGNES CI-DESSOUS NE CHANGENT JAMAIS
try{
    // On écrit le DSN de connexion
    $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME . ';charset=utf8';

    // On se connecte à la base
    $db = new PDO($dsn, DBUSER, DBPASS);

    // On définit le fetch par défaut à FETCH_ASSOC
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // On force le jeu de caractères à UTF8
    $db->setAttribute(PDO\Mysql::ATTR_INIT_COMMAND, 'SET NAMES utf8');

    // On définit le mode de gestion des erreurs
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $exception){
    die('Une erreur est survenue : ' . $exception->getMessage());
}