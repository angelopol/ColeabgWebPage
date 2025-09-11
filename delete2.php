<?php
require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/layout.php';
require_auth();
if (!verify_csrf()) { render_header('Token inválido','domo.jpg'); echo alert_box('danger','Token CSRF inválido.'); render_footer(); exit; }
if (empty($_SESSION['delete_flow'])) { redirect('delete.php'); }
render_header('Confirmar Eliminación', 'domo.jpg');
?>
<div class="min-h-screen flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-md">
    <div class="rounded-2xl border border-rose-500/30 bg-rose-900/10 backdrop-blur-sm p-8 shadow-lg">
      <h1 class="text-lg font-semibold text-rose-300 mb-5">Confirma tu contraseña</h1>
      <form action="delete3.php" method="post" novalidate class="space-y-5">
        <?= csrf_input(); ?>
        <div>
          <label class="block text-sm font-medium text-neutral-200 mb-1">Contraseña</label>
          <input name="contra" type="password" required minlength="8" class="w-full rounded-md bg-neutral-800/60 border border-neutral-600/60 px-3 py-2 text-neutral-100 focus:outline-none focus:ring-2 focus:ring-rose-400/50 focus:border-rose-400 placeholder-neutral-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-neutral-200 mb-1">Confirmar contraseña</label>
          <input name="contra2" type="password" required minlength="8" class="w-full rounded-md bg-neutral-800/60 border border-neutral-600/60 px-3 py-2 text-neutral-100 focus:outline-none focus:ring-2 focus:ring-rose-400/50 focus:border-rose-400 placeholder-neutral-500">
        </div>
        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-rose-400/40 bg-rose-500/20 px-5 py-2.5 text-sm font-medium text-rose-100 hover:bg-rose-500/30 focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition">Eliminar Cuenta</button>
      </form>
      <div class="mt-6 text-center">
        <a href="pageuser.php" class="inline-flex justify-center rounded-md bg-neutral-700/60 hover:bg-neutral-600 text-neutral-100 text-sm font-medium px-5 py-2 transition focus:outline-none focus:ring focus:ring-neutral-400/40">Cancelar</a>
      </div>
    </div>
  </div>
</div>
<?php render_footer(); ?>