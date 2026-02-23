<?php
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /gymbook/pages/login.php');
    exit;
}

$course_id = (int) $_GET['id'];
$user_id   = $_SESSION['user_id'];
$error     = '';

// Controlla che il corso esista
$stmt = $pdo->prepare('SELECT * FROM courses WHERE id = ?');
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    header('Location: /gymbook/pages/courses.php');
    exit;
}

// Controlla che l'utente non sia già prenotato
$stmt = $pdo->prepare('
    SELECT id FROM bookings 
    WHERE user_id = ? AND course_id = ? AND status = "confirmed"
');
$stmt->execute([$user_id, $course_id]);
if ($stmt->fetch()) {
    header('Location: /gymbook/pages/courses.php');
    exit;
}

// Controlla i posti disponibili
$stmt = $pdo->prepare('
    SELECT c.max_slots - COUNT(b.id) AS available_slots
    FROM courses c
    LEFT JOIN bookings b ON c.id = b.course_id AND b.status = "confirmed"
    WHERE c.id = ?
    GROUP BY c.id
');
$stmt->execute([$course_id]);
$result = $stmt->fetch();

if (!$result || $result['available_slots'] <= 0) {
    $error = 'Corso al completo, non è possibile prenotare.';
} else {
    // Tutto ok — salva la prenotazione
    $stmt = $pdo->prepare('
        INSERT INTO bookings (user_id, course_id) VALUES (?, ?)
    ');
    $stmt->execute([$user_id, $course_id]);

    header('Location: /gymbook/pages/dashboard.php');
    exit;
}
?>

<?php if ($error): ?>
    <div class="card">
        <div class="alert alert-error"><?php echo htmlspecialchars($error) ?></div>
        <a href="courses.php" class="btn btn-secondary">Torna ai corsi</a>
    </div>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>