<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Protezione — solo gli admin possono entrare
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /gymbook/pages/dashboard.php');
    exit;
}

$error   = '';
$success = '';

// ELIMINA corso
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare('DELETE FROM bookings WHERE course_id = ?')->execute([$id]);
    $pdo->prepare('DELETE FROM courses WHERE id = ?')->execute([$id]);
    $success = 'Corso eliminato con successo.';
}

// CREA o MODIFICA corso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $instructor  = trim($_POST['instructor']);
    $schedule    = trim($_POST['schedule']);
    $max_slots   = (int) $_POST['max_slots'];
    $edit_id     = (int) $_POST['edit_id'];

    if (empty($name) || empty($instructor) || empty($schedule) || $max_slots <= 0) {
        $error = 'Tutti i campi sono obbligatori e i posti devono essere maggiori di zero.';
    } else {
        if ($edit_id > 0) {
            // Modifica corso esistente
            $stmt = $pdo->prepare('
                UPDATE courses 
                SET name = ?, description = ?, instructor = ?, schedule = ?, max_slots = ?
                WHERE id = ?
            ');
            $stmt->execute([$name, $description, $instructor, $schedule, $max_slots, $edit_id]);
            $success = 'Corso modificato con successo.';
        } else {
            // Crea nuovo corso
            $stmt = $pdo->prepare('
                INSERT INTO courses (name, description, instructor, schedule, max_slots)
                VALUES (?, ?, ?, ?, ?)
            ');
            $stmt->execute([$name, $description, $instructor, $schedule, $max_slots]);
            $success = 'Corso creato con successo.';
        }
    }
}

// Carica corso da modificare (se è stato cliccato "Modifica")
$editing = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM courses WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $editing = $stmt->fetch();
}

// Carica tutti i corsi
$courses = $pdo->query('
    SELECT c.*, COUNT(b.id) AS booked
    FROM courses c
    LEFT JOIN bookings b ON c.id = b.course_id AND b.status = "confirmed"
    GROUP BY c.id
    ORDER BY c.name ASC
')->fetchAll();
?>

<div class="card">
    <h1>⚙️ Gestione Corsi</h1>
</div>

<!-- FORM CREA / MODIFICA -->
<div class="card">
    <h2><?php echo $editing ? 'Modifica Corso' : 'Aggiungi Nuovo Corso' ?></h2>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="edit_id" value="<?php echo $editing['id'] ?? 0 ?>">

        <div class="form-group">
            <label>Nome corso</label>
            <input type="text" name="name" 
                   value="<?php echo htmlspecialchars($editing['name'] ?? $_POST['name'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Descrizione</label>
            <textarea name="description" rows="3"><?php 
                echo htmlspecialchars($editing['description'] ?? $_POST['description'] ?? '') 
            ?></textarea>
        </div>
        <div class="form-group">
            <label>Istruttore</label>
            <input type="text" name="instructor" 
                   value="<?php echo htmlspecialchars($editing['instructor'] ?? $_POST['instructor'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Orario (es. Lunedì e Mercoledì 09:00)</label>
            <input type="text" name="schedule" 
                   value="<?php echo htmlspecialchars($editing['schedule'] ?? $_POST['schedule'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Posti massimi</label>
            <input type="number" name="max_slots" min="1"
                   value="<?php echo htmlspecialchars($editing['max_slots'] ?? $_POST['max_slots'] ?? 10) ?>">
        </div>

        <button type="submit" class="btn btn-primary">
            <?php echo $editing ? 'Salva Modifiche' : 'Crea Corso' ?>
        </button>

        <?php if ($editing): ?>
            <a href="courses.php" class="btn btn-secondary">Annulla</a>
        <?php endif; ?>
    </form>
</div>

<!-- LISTA CORSI -->
<div class="card">
    <h2>Corsi Esistenti</h2>
    <?php if (empty($courses)): ?>
        <p>Nessun corso presente.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Istruttore</th>
                    <th>Orario</th>
                    <th>Posti</th>
                    <th>Prenotati</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $c): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['name']) ?></td>
                        <td><?php echo htmlspecialchars($c['instructor']) ?></td>
                        <td><?php echo htmlspecialchars($c['schedule']) ?></td>
                        <td><?php echo $c['max_slots'] ?></td>
                        <td><?php echo $c['booked'] ?> / <?php echo $c['max_slots'] ?></td>
                        <td>
                            <a href="courses.php?edit=<?php echo $c['id'] ?>" 
                               class="btn btn-secondary">Modifica</a>
                            <a href="courses.php?delete=<?php echo $c['id'] ?>"
                               class="btn btn-danger"
                               onclick="return confirm('Eliminare questo corso e tutte le sue prenotazioni?')">
                               Elimina
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>