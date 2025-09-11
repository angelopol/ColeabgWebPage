<?php
require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/layout.php';
require __DIR__ . '/src/Repositories.php';
require __DIR__ . '/src/nav.php';

if (empty($_SESSION['nameuser'])) { redirect('index.php'); }
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1600)) { session_unset(); session_destroy(); redirect('index.php'); }
$_SESSION['LAST_ACTIVITY'] = time();

render_header('Cambiar Contraseña', 'domo.jpg');
render_auth_nav('Menu');
?>
<div class="w-full flex justify-center items-center min-h-screen px-4">
  <div class="w-full max-w-2xl bg-slate-900/70 backdrop-blur rounded-xl p-8 shadow-lg">
    <h1 class="text-2xl font-bold text-white mb-6">Cambia tu Contraseña</h1>
    <form action="recpass2.php" method="post" class="space-y-6">
      <?= csrf_input(); ?>
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold text-slate-300 mb-1">Contraseña Actual</label>
          <input name="contra" type="password" pattern=".{8,}" required placeholder="Antigua" class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/40 text-slate-100 px-3 py-2 text-sm outline-none" />
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-300 mb-1">Confirmar Actual</label>
          <input name="contra2" type="password" pattern=".{8,}" required placeholder="Confirmar" class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/40 text-slate-100 px-3 py-2 text-sm outline-none" />
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-300 mb-1">Nueva Contraseña</label>
          <input name="contranew" type="password" pattern=".{8,}" required placeholder="Mínimo 8 caracteres" class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/40 text-slate-100 px-3 py-2 text-sm outline-none" />
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-300 mb-1">Confirmar Nueva</label>
          <input name="contranew2" type="password" pattern=".{8,}" required placeholder="Confirmar" class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/40 text-slate-100 px-3 py-2 text-sm outline-none" />
        </div>
      </div>
      <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-sm rounded-md px-4 py-2 transition">Cambiar</button>
    </form>
    <div class="flex gap-3 mt-6 flex-wrap">
      <a href='logout.php' class='flex-1 text-center bg-rose-600 hover:bg-rose-500 text-white font-medium text-sm rounded-md px-4 py-2 transition'>Cerrar sesión</a>
      <form action='index.php' class='flex-1'>
        <button type='submit' class='w-full bg-amber-500 hover:bg-amber-400 text-white font-medium text-sm rounded-md px-4 py-2 transition'>Salir</button>
      </form>
    </div>
  </div>
</div>
<?php render_footer(); ?>