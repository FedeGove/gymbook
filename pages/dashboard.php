<?php
require_once '../config/database.php';
require_once '../includes/header.php';

// Protezione pagina — se non sei loggato ti rimanda al login
if (!isset($_SESSION['user_id'])) {
    header('Location: /gymbook/pages/login.php');
    exit;
}

// Carica le prenotazioni dell'utente
$stmt = $pdo->prepare('
    SELECT b.id, b.status, b.created_at, c.name, c.instructor, c.schedule
    FROM bookings b
    JOIN courses c ON b.course_id = c.id
    WHERE b.user_id = ? AND b.status = "confirmed"
    ORDER BY b.created_at DESC
');
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();
?>

<div class="card">
    <h1>Bentornato, <?php echo htmlspecialchars($_SESSION['user_name']) ?>! 👋</h1>
    <p>Ecco le tue prenotazioni attive.</p>
</div>

<div class="card">
    <h2>Le mie prenotazioni</h2>

    <?php if (empty($bookings)): ?>
        <p>Non hai ancora prenotazioni. <a href="courses.php">Sfoglia i corsi disponibili!</a></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Corso</th>
                    <th>Istruttore</th>
                    <th>Orario</th>
                    <th>Prenotato il</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($b['name']) ?></td>
                        <td><?php echo htmlspecialchars($b['instructor']) ?></td>
                        <td><?php echo htmlspecialchars($b['schedule']) ?></td>
                        <td><?php echo date('d/m/Y', strtotime($b['created_at'])) ?></td>
                        <td>
                            <a href="cancel_booking.php?id=<?php echo $b['id'] ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Vuoi cancellare questa prenotazione?')">
                               Cancella
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>