<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';

// Prefer posted 'user' when present; otherwise allow cookie-only flows.
$postedUser = trim($_POST['user'] ?? '');
$userjoined = $postedUser !== '' ? $postedUser : ($_COOKIE['user'] ?? '');

if ($userjoined === '') {
    // No user supplied and no cookie: redirect back to entry form.
    header('Location: ingre_workers.php');
    exit;
}

$valid = false;
try {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT TOP 1 email FROM USUARIOS WHERE email = ? AND pass IS NULL AND Clase IS NULL AND CodClie IS NULL");
    $stmt->execute([$userjoined]);
    $valid = (bool)$stmt->fetchColumn();
} catch (Throwable $e) {
    error_log('[login_workers] ' . $e->getMessage());
    $valid = false;
}

if ($valid) {
    // Refresh cookie regardless of whether it came from POST or existing cookie
    setcookie('user', $userjoined, time() + 3600 * 24 * 30, '/');
    header('Location: workers.php');
    exit;
}

render_header('Datos incorrectos', 'domo.jpg');
?>
<div class="min-h-screen px-4 py-12">
    <div class="max-w-md mx-auto">
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 shadow space-y-6">
            <h1 class="text-xl font-semibold text-white tracking-tight">Error</h1>
            <div class="rounded-lg px-4 py-3 text-sm font-medium bg-rose-500/15 text-rose-200 border border-rose-400/30">
                Datos ingresados de forma incorrecta.
            </div>
            <form action="ingre_workers.php" class="pt-2">
                <button type="submit" class="w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition focus:outline-none focus:ring focus:ring-indigo-400/50">Intente de nuevo</button>
            </form>
        </div>
    </div>
</div>
<?php render_footer(); ?>