<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
// Start session only if not already active to avoid PHP Notice when bootstrap/layout started it earlier
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_COOKIE['user'])) { header('Location: ingre_workers.php'); exit; }
$_SESSION['assist'] = 1;
// Allow geolocation via Permissions-Policy header (must be sent before any output). Some hosts may override headers; adjust if needed.
header('Permissions-Policy: geolocation=(self)');
render_header('Asistencia', 'domo.jpg');
$username = h($_COOKIE['user']);
?>
<div class="min-h-screen px-4 py-12" data-user="<?= $username ?>">
    <div class="max-w-xl mx-auto space-y-10">
        <div class="text-center space-y-2">
            <h1 class="text-3xl font-semibold text-white tracking-tight">Marca tu asistencia</h1>
            <p class="text-xl font-mono text-indigo-200"><?= $username ?></p>
        </div>
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 shadow space-y-6">
            <div class="grid sm:grid-cols-2 gap-6">
                <button id="btnIniciar" type="button" class="inline-flex justify-center items-center rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-6 py-4 text-lg transition focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
                    Entrada
                </button>
                <button id="btnDetener" type="button" class="inline-flex justify-center items-center rounded-lg bg-rose-600 hover:bg-rose-500 text-white font-medium px-6 py-4 text-lg transition focus:outline-none focus:ring-2 focus:ring-rose-400/50">
                    Salida
                </button>
            </div>
            <p id="message" class="text-center text-sm text-neutral-200 min-h-[1.5rem]"></p>
            <div class="pt-4 grid sm:grid-cols-2 gap-4 text-sm">
                <p id="latitud" class="text-neutral-300"></p>
                <p id="longitud" class="text-neutral-300"></p>
            </div>
        </div>
    </div>
</div>
<script>
// Ensure the username is available on body for the script (some layouts attach data to a container element)
document.addEventListener('DOMContentLoaded', function(){
    try { document.body.setAttribute('data-temp', <?= json_encode($username) ?>); } catch (e) {}
});
</script>
<script src="workers.js"></script>
<?php render_footer(); ?>