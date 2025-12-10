<?php
// ----------------------------------------------------
// 1. Définition des paramètres de connexion (Local Laragon/MAMP)
// ----------------------------------------------------

// Les informations à inclure seront différentes selon que vous hébergerez localement ou sur alwaysdata.
$host     = 'localhost';            // Hébergement local
$dbname   = 'super-d-fi-photo';     // Nom de la BDD utilisé par l'équipe (doit être le même pour tous en local)
$username = 'root';                 // Identifiant par défaut sur MAMP/Laragon
$password = 'root';                 // Mot de passe par défaut sur MAMP/Laragon

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// ----------------------------------------------------
// 2. Création de l'objet PDO
// ----------------------------------------------------
try {
    // Création de l'élément $pdo qui représente la connexion à la base de données
    $pdo = new PDO($dsn, $username, $password);

    // Configuration pour gérer les erreurs PDO en tant qu'exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Affichage de l'erreur et arrêt du script en cas d'échec de connexion
    die("Échec de la connexion à la base de données : " . $e->getMessage());
}

// Vos camarades développeurs pourront inclure ce fichier en utilisant require_once 'connect.php';
?>