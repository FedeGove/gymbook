<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Se già loggato, vai alla dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: /gymbook/pages/dashboard.php');
    exit;
}

// Carica alcuni corsi da mostrare in anteprima
$courses = $pdo->query('
    SELECT c.*, c.max_slots - COUNT(b.id) AS available_slots
    FROM courses c
    LEFT JOIN bookings b ON c.id = b.course_id AND b.status = "confirmed"
    GROUP BY c.id
    ORDER BY c.name ASC
    LIMIT 3
')->fetchAll();
?>

<!-- HERO -->
<div class="hero">
    <h1>Benvenuto su 💪 GymBook</h1>
    <p>Prenota i tuoi corsi preferiti in pochi click.</p>
    <div style="display:flex; gap:1rem; justify-content:center; margin-top:1.5rem;">
        <a href="pages/register.php" class="btn btn-primary">Inizia ora</a>
        <a href="pages/login.php" class="btn btn-secondary">Accedi</a>
    </div>
</div>

<!-- ANTEPRIMA CORSI -->
<div class="card" style="margin-top:2rem;">
    <h2>I nostri corsi</h2>
    <p style="color:#666; margin-bottom:1.5rem;">
        Scopri cosa offre la nostra palestra.
    </p>

    <?php foreach ($courses as $course): ?>
        <div class="course-preview">
            <div>
                <h3><?php echo htmlspecialchars($course['name']) ?></h3>
                <p><?php echo htmlspecialchars($course['description']) ?></p>
                <small>👤 <?php echo htmlspecialchars($course['instructor']) ?> 
                       &nbsp;|&nbsp; 
                       🕐 <?php echo htmlspecialchars($course['schedule']) ?>
                </small>
            </div>
            <span class="badge <?php echo $course['available_slots'] > 0 ? 'badge-green' : 'badge-red' ?>">
                <?php echo $course['available_slots'] > 0 ? $course['available_slots'] . ' posti liberi' : 'Completo' ?>
            </span>
        </div>
    <?php endforeach; ?>

    <div style="text-align:center; margin-top:1.5rem;">
        <a href="pages/register.php" class="btn btn-primary">
            Registrati per prenotare
        </a>
    </div>
</div>

<!-- FEATURES -->
<div class="features">
    <div class="feature-card">
        <div class="feature-icon">📅</div>
        <h3>Prenota facilmente</h3>
        <p>Scegli il corso e prenota il tuo posto in un click.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">✅</div>
        <h3>Gestisci le prenotazioni</h3>
        <p>Visualizza e cancella le tue prenotazioni quando vuoi.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">👥</div>
        <h3>Posti limitati</h3>
        <p>I posti si aggiornano in tempo reale — non perdere il tuo!</p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>