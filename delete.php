<?php
require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/layout.php';
require_auth();
// token to ensure user passed through confirmation flow
$_SESSION['delete_flow'] = time();
render_header('Eliminar Usuario', 'domo.jpg');
?>
<div class="min-h-screen flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-lg space-y-6">
    <div class="rounded-2xl border border-rose-500/30 bg-rose-900/10 backdrop-blur-sm p-8 shadow-lg">
      <h1 class="text-xl font-semibold text-rose-300 mb-3 text-center">¿Seguro que deseas eliminar tu usuario?</h1>
      <p class="text-sm text-rose-100/80 mb-6 text-center">Esta acción es permanente y eliminará tu cuenta de acceso.</p>
      <form action="delete2.php" method="post" class="space-y-4">
        <?= csrf_input(); ?>
        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-md border border-rose-400/40 bg-rose-500/20 px-5 py-2.5 text-sm font-medium text-rose-100 hover:bg-rose-500/30 focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition">
          Sí, continuar
        </button>
      </form>
      <div class="mt-4 text-center">
        <a href="pageuser.php" class="inline-flex justify-center rounded-md bg-neutral-700/60 hover:bg-neutral-600 text-neutral-100 text-sm font-medium px-5 py-2 transition focus:outline-none focus:ring focus:ring-neutral-400/40">Cancelar</a>
      </div>
    </div>
    <div class="text-center">
      <a href="soport.php" class="inline-block opacity-80 hover:opacity-100 transition"><img src="contact2.png" width="80" alt="Soporte" class="mx-auto"></a>
    </div>
  </div>
</div>
<?php render_footer(); ?>