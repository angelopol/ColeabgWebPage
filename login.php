<?php
require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/Repositories.php';

$email = $_POST['user'] ?? '';
$pass  = $_POST['password'] ?? '';

if (!verify_csrf()) {
    log_security_event('csrf_login_fail', ['email' => $email, 'ip' => $_SERVER['REMOTE_ADDR'] ?? '']);
    redirect('ingre.php');
}

if ($email === '' || $pass === '') {
        redirect('index.php');
}

$userRepo = new UserRepository(db());
$user = $userRepo->verifyPassword($email, $pass); // Supports legacy plain or hashed.

if ($user) {
    log_security_event('login_success', ['email' => $email, 'ip' => $_SERVER['REMOTE_ADDR'] ?? '']);
        // Auto-upgrade legacy plain passwords to bcrypt transparently
        if (!str_starts_with((string)$user['pass'], '$2y$')) {
            // Re-hash silently; ignore failure (user can still login next time)
            try {
                $userRepo->updatePasswordHashed($email, $pass);
            } catch (Throwable $e) {
                error_log('Password auto-upgrade failed for ' . $email . ': ' . $e->getMessage());
            }
        }
        $_SESSION['nameuser'] = $email;
    // Session fixation mitigation (layout will also enforce, but explicit here for early pages without layout)
    if (function_exists('ensure_session_regenerated')) { ensure_session_regenerated(); }
        redirect('pageuser.php');
}
log_security_event('login_failed', ['email' => $email, 'ip' => $_SERVER['REMOTE_ADDR'] ?? '']);

require __DIR__ . '/src/layout.php';
render_header('Datos incorrectos', 'domo.jpg');
?>
    <div class="container">
        <div class="row min-vh-100 justify-content-center align-items-center">
            <div class="col-auto p-5">
                <div class='alert alert-danger' role='alert'>Datos ingresados de forma incorrecta!</div>
                <br>
                <form action='ingre.php'>
                    <button type='submit' class='btn btn-primary'>Intente de nuevo</button>
                </form>
                <br>
                <form action='index.php'>
                    <button type='submit' class='btn btn-warning'>Salir</button>
                </form>
            </div>
        </div>
    </div>
    <?php render_footer(); ?>