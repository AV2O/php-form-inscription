<?php
// creation du mot de passe
$password = "brouette";
// hashage du mot de passe
$passwordHash1 = password_hash($password, PASSWORD_ARGON2ID);
// Verifier si le password est compatible au hash
$password_verify('brouette', $passwordHash1);