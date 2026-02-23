<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Tutti i campi sono obbligatori.';
    } else {
        // Cerca l'utente nel database
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login riuscito — salva i dati in sessione
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            header('Location: /gymbook/pages/dashboard.php');
            exit;
        } else {
            $error = 'Email o password errati.';
        }
    }
}
?>

<div class="card">
    <h1>Login</h1>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Accedi</button>
        <a href="register.php" class="btn btn-secondary">Non hai un account?</a>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>