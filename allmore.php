<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/Repositories.php';
require_once __DIR__ . '/src/layout.php';

$ci = trim($_GET['ci'] ?? '');
$ip = (int)($_GET['ip'] ?? 0);
if ($ci === '') redirect('index.php');

$repo = new OperationsRepository(db());
$rows = $repo->allOperationsSAACXCByClient($ci);

render_header('Verifica las operaciones', 'domo.jpg');
?>
<div class="min-h-screen px-4 py-12">
  <div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold text-white tracking-tight mb-6">Operaciones (Extendido)</h1>
    <div class="space-y-8">
      <?php foreach ($rows as $row): ?>
        <div class="rounded-xl border border-white/10 bg-white/5 backdrop-blur-sm p-5 shadow-sm hover:shadow transition">
          <p class="text-lg font-semibold text-white mb-2"><?= h($row['FechaE']) ?></p>
          <p class="text-sm leading-relaxed text-neutral-200">
            <span class="font-mono text-indigo-200"><?= h($row['Document'] ?? '') ?></span><br>
            <?= h(trim(($row['Notas1'] ?? '') . ' ' . ($row['Notas2'] ?? '') . ' ' . ($row['Notas3'] ?? '') . ' ' . ($row['Notas4'] ?? '') . ' ' . ($row['Notas5'] ?? '') . ' ' . ($row['Notas6'] ?? '') . ' ' . ($row['Notas7'] ?? ''))) ?>
          </p>
        </div>
      <?php endforeach; ?>
      <?php if (empty($rows)): ?>
        <p class="text-neutral-300">No se encontraron operaciones.</p>
      <?php endif; ?>
    </div>
    <div class="mt-10 space-y-4">
      <?php if ($ip === 1): ?>
        <form action="search2.php">
          <button type="submit" class="w-full inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-4 py-2 transition focus:outline-none focus:ring focus:ring-emerald-400/50">Realizar otra búsqueda</button>
        </form>
      <?php elseif ($ip === 0): ?>
        <form action="search.php">
          <button type="submit" class="w-full inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-4 py-2 transition focus:outline-none focus:ring focus:ring-emerald-400/50">Realizar otra búsqueda</button>
        </form>
      <?php elseif ($ip === 2): ?>
        <form action="pageuser.php">
          <button type="submit" class="w-full inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-4 py-2 transition focus:outline-none focus:ring focus:ring-emerald-400/50">Regresar</button>
        </form>
      <?php endif; ?>
      <form action="index.php">
        <button type="submit" class="inline-flex justify-center rounded-md bg-amber-500 hover:bg-amber-400 text-neutral-900 font-medium px-5 py-2 transition focus:outline-none focus:ring focus:ring-amber-400/50">Salir</button>
      </form>
    </div>
  </div>
</div>
<?php render_footer(); ?>