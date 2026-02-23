<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymBook</title>
    <link rel="stylesheet" href="/gymbook/assets/style.css">
</head>
<body>

<nav>
    <div class="nav-brand">💪 GymBook</div>
    <div class="nav-links">
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Ciao, <?php echo htmlspecialchars($_SESSION['user_name']) ?>!</span>
            <a href="/gymbook/pages/courses.php">Corsi</a>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="/gymbook/pages/admin/courses.php">Gestione Corsi</a>
                <a href="/gymbook/pages/admin/bookings.php">Prenotazioni</a>
            <?php endif; ?>
            <a href="/gymbook/pages/logout.php">Logout</a>
        <?php else: ?>
            <a href="/gymbook/pages/login.php">Login</a>
            <a href="/gymbook/pages/register.php">Registrati</a>
        <?php endif; ?>
    </div>
</nav>

<main>