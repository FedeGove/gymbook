<?php
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /gymbook/pages/login.php');
    exit;
}

// Carica tutti i corsi con il numero di posti disponibili
$stmt = $pdo->prepare('
    SELECT 
        c.*,
        c.max_slots - COUNT(b.id) AS available_slots,
        MAX(CASE WHEN b.user_id = ? THEN 1 ELSE 0 END) AS already_booked
    FROM courses c
    LEFT JOIN bookings b ON c.id = b.course_id AND b.status = "confirmed"
    GROUP BY c.id
');
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll();
?>

<div class="card">
    <h1>Corsi Disponibili 🏋️</h1>
    <p>Prenota il tuo posto nei corsi della palestra.</p>
</div>

<?php if (empty($courses)): ?>
    <div class="card">
        <p>Nessun corso disponibile al momento.</p>
    </div>
<?php else: ?>
    <?php foreach ($courses as $course): ?>
        <div class="card course-card">
            <div class="course-header">
                <h2><?php echo htmlspecialchars($course['name']) ?></h2>
                <span class="badge <?php echo $course['available_slots'] > 0 ? 'badge-green' : 'badge-red' ?>">
                    <?php echo $course['available_slots'] > 0 ? $course['available_slots'] . ' posti liberi' : 'Completo' ?>
                </span>
            </div>

            <p class="course-desc"><?php echo htmlspecialchars($course['description']) ?></p>

            <div class="course-info">
                <span>👤 <?php echo htmlspecialchars($course['instructor']) ?></span>
                <span>🕐 <?php echo htmlspecialchars($course['schedule']) ?></span>
                <span>👥 Max <?php echo $course['max_slots'] ?> persone</span>
            </div>

            <div class="course-action">
                <?php if ($course['already_booked']): ?>
                    <span class="btn btn-secondary" style="opacity:0.6; cursor:default;">
                        ✅ Già prenotato
                    </span>
                <?php elseif ($course['available_slots'] <= 0): ?>
                    <span class="btn btn-danger" style="opacity:0.6; cursor:default;">
                        ❌ Completo
                    </span>
                <?php else: ?>
                    <a href="book_course.php?id=<?php echo $course['id'] ?>" class="btn btn-primary">
                        Prenota
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>