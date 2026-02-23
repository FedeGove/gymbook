<?php
require_once '../config/database.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /gymbook/pages/login.php');
    exit;
}

$booking_id = (int) $_GET['id'];
$user_id    = $_SESSION['user_id'];

// Verifica che la prenotazione appartenga all'utente loggato
$stmt = $pdo->prepare('
    SELECT id FROM bookings WHERE id = ? AND user_id = ?
');
$stmt->execute([$booking_id, $user_id]);

if ($stmt->fetch()) {
    $stmt = $pdo->prepare('
        UPDATE bookings SET status = "cancelled" WHERE id = ?
    ');
    $stmt->execute([$booking_id]);
}

header('Location: /gymbook/pages/dashboard.php');
exit;