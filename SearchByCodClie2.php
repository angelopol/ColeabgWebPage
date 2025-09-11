<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';

if (!verify_csrf()) {
    http_response_code(422);
    $error = 'Token CSRF inválido.';
}

$input = trim($_POST['input'] ?? '');
$error = $error ?? null;
$results = [];

if (!isset($error) && $input === '') {
    $error = 'Debe indicar la cédula.';
}

if (!isset($error)) {
    try {
        $pdo = db();
        // Search lawyer candidates by CodClie or ID3 like the existing rule
        $stmt1 = $pdo->prepare('SELECT TOP 10 CodClie, NomClie, Clase FROM SACLIE WHERE CodClie LIKE ?');
        $stmt2 = $pdo->prepare('SELECT TOP 10 CodClie, NomClie, Clase FROM SACLIE WHERE ID3 LIKE ?');
        $like = '%' . $input . '%';
        $stmt1->execute([$like]);
        $stmt2->execute([$like]);
        $a = $stmt1->fetchAll(PDO::FETCH_ASSOC);
        $b = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        // Merge unique by CodClie
        $byCod = [];
        foreach (array_merge($a, $b) as $row) { $byCod[$row['CodClie']] = $row; }
        $results = array_values($byCod);
        if (!$results) { $error = 'No se encontraron coincidencias.'; }
    } catch (Throwable $e) {
        error_log('[SearchByCodClie2] ' . $e->getMessage());
        $error = 'Error interno.';
    }
}

render_header('Resultados de Búsqueda', 'piscina.jpg');
?>
<div class="min-h-screen px-4 py-10">
  <div class="max-w-3xl mx-auto">
    <div class="space-y-4">
      <?php if($error): ?>
        <div class="rounded-md px-4 py-3 text-sm bg-rose-500/15 text-rose-200 border border-rose-400/30"><?php echo h($error); ?></div>
      <?php else: ?>
        <?php foreach($results as $law): ?>
          <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-5 text-white/90">
            <div class="flex items-center justify-between gap-4">
              <div>
                <div class="text-sm"><span class="text-white/60">CodClie:</span> <?php echo h($law['CodClie']); ?></div>
                <div class="text-sm"><span class="text-white/60">Nombre:</span> <?php echo h($law['NomClie']); ?></div>
                <div class="text-sm"><span class="text-white/60">Inpre:</span> <?php echo h($law['Clase']); ?></div>
              </div>
              <form action="SearchCodClie.php" method="post" class="m-0">
                <?php echo csrf_input(); ?>
                <input type="hidden" name="ci" value="<?php echo h($law['CodClie']); ?>" />
                <button class="inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-5 py-2.5">Ver Operaciones</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
      <div class="flex items-center gap-3 pt-2">
        <a href="SearchByCodClie.php" class="flex-1 inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-2.5">Volver</a>
        <a href="index.php" class="inline-flex justify-center rounded-md bg-slate-600 hover:bg-slate-500 text-white font-medium px-6 py-2.5">Inicio</a>
      </div>
    </div>
  </div>
</div>
<?php render_footer(); ?>
