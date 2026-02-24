<?php

// Copia questo file in database.php e inserisci le tue credenziali
$db_host = 'localhost';
$db_name = 'nome_database';
$db_user = 'username';
$db_pass = 'password';

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore di connessione: " . $e->getMessage());
}
