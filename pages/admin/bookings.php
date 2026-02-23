<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /gymbook/pages/dashboard.php');
    exit;
}

// Filtro per corso
$filter_course = (int) ($_GET['course_id'] ?? 0);

$query = '
    SELECT b.id, b.status, b.created_at,
           u.name AS user_name, u.email,
           c.name AS course_name, c.schedule
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN courses c ON b.course_id = c.id
';

if ($filter_course > 0) {
    $query .= ' WHERE b.course_id = ?';
    $query .= ' ORDER BY b.created_at DESC';
    $stmt = $pdo->prepare($query);
    $stmt->execute([$filter_course]);
} else {
    $query .= ' ORDER BY b.created_at DESC';
    $stmt = $pdo->query($query);
}

$bookings = $stmt->fetchAll();

// Carica lista corsi per il filtro
$courses = $pdo->query('SELECT id, name FROM courses ORDER BY name ASC')->fetchAll();
?>

<div class="card">
    <h1>📋 Tutte le Prenotazioni</h1>

    <!-- Filtro per corso -->
    <form method="GET" style="display:flex; gap:1rem; align-items:center; margin-top:1rem;">
        <select name="course_id" class="form-select">
            <option value="0">Tutti i corsi</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?php echo $c['id'] ?>" 
                    <?php echo $filter_course === (int)$c['id'] ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filtra</button>
        <?php if ($filter_course > 0): ?>
            <a href="bookings.php" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <p style="margin-bottom:1rem; color:#666;">
        Totale: <strong><?php echo count($bookings) ?> prenotazioni</strong>
    </p>

    <?php if (empty($bookings)): ?>
        <p>Nessuna prenotazione trovata.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Utente</th>
                    <th>Email</th>
                    <th>Corso</th>
                    <th>Orario</th>
                    <th>Stato</th>
                    <th>Data prenotazione</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($b['user_name']) ?></td>
                        <td><?php echo htmlspecialchars($b['email']) ?></td>
                        <td><?php echo htmlspecialchars($b['course_name']) ?></td>
                        <td><?php echo htmlspecialchars($b['schedule']) ?></td>
                        <td>
                            <span class="badge <?php echo $b['status'] === 'confirmed' ? 'badge-green' : 'badge-red' ?>">
                                <?php echo $b['status'] === 'confirmed' ? 'Confermata' : 'Cancellata' ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($b['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>