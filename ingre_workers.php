<?php
session_start();
if (isset($_COOKIE['user'])) { header('Location: login_workers.php'); exit; }
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
render_header('Ingreso Asistencia', 'domo.jpg');
?>
<div class="min-h-screen px-4 py-12">
    <div class="max-w-md mx-auto">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-semibold text-white tracking-tight">Marca tu asistencia</h1>
            <p class="text-neutral-300 text-sm mt-2">Ingrese su usuario para continuar.</p>
        </div>
        <form action="login_workers.php" method="post" class="space-y-6 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 shadow">
            <?= csrf_input(); ?>
            <div>
                <label class="block text-sm font-medium text-neutral-200 mb-1">Usuario</label>
                <input name="user" type="text" required class="w-full rounded-md bg-neutral-800/60 border border-neutral-600/60 px-4 py-2.5 text-neutral-100 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400" placeholder="correo o ID">
            </div>
            <button type="submit" class="w-full inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-5 py-2.5 transition focus:outline-none focus:ring focus:ring-emerald-400/50">Ingresar</button>
        </form>
    </div>
</div>
<?php render_footer(); ?>