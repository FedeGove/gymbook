<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Validazione
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Tutti i campi sono obbligatori.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email non valida.';
    } elseif (strlen($password) < 6) {
        $error = 'La password deve essere di almeno 6 caratteri.';
    } elseif ($password !== $confirm) {
        $error = 'Le password non coincidono.';
    } else {
        // Controlla se l'email esiste già
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = 'Questa email è già registrata.';
        } else {
            // Salva l'utente
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $hashed]);

            $success = 'Registrazione completata! Puoi ora fare il login.';
        }
    }
}
?>

<div class="card">
    <h1>Registrati</h1>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password">
        </div>
        <div class="form-group">
            <label>Conferma Password</label>
            <input type="password" name="confirm_password">
        </div>
        <button type="submit" class="btn btn-primary">Registrati</button>
        <a href="login.php" class="btn btn-secondary">Hai già un account?</a>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>