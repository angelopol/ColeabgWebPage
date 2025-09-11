<?php
// Refactored controller style logic for searching operations (system.php)
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/Repositories.php';

$input = trim($_REQUEST['input'] ?? '');
$inputYear = trim($_REQUEST['input2'] ?? '');
$year = valid_year_or_null($inputYear);

if ($input === '') {
  redirect('index.php');
}

$db = db();
$lawRepo = new LawyerRepository($db);
$opsRepo = new OperationsRepository($db);

// Determine lawyer record (prefer exact CodClie equality, else ID3)
// Attempt direct (pattern) matches; gather potential multiples
$primaryMatch = $lawRepo->findByCedula($input);
if (!$primaryMatch) {
  $primaryMatch = $lawRepo->findById3($input);
}
// Always search broader to allow user disambiguation if more than one
$candidates = $lawRepo->findByCedulaOrId3Like($input);
// If we have an exact-like match and more than one candidate, we'll present selection.
$multiple = count($candidates) > 1;
$lawyer = null;

// If user selected a specific CodClie via query param choose that.
$selected = $_GET['cod'] ?? null;
if ($selected) {
  foreach ($candidates as $c) {
    if (isset($c['CodClie']) && (string)$c['CodClie'] === (string)$selected) { $lawyer = $c; break; }
  }
}
if (!$lawyer) {
  $lawyer = $primaryMatch ?: ($multiple ? null : ($candidates[0] ?? null));
}

$insc = null; $solv = null; $operations = []; $groupedRecent = []; $hasHistoric = false; $hasAnyOtherYear = false; $notFound = false;
if ($lawyer) {
  $insc = $lawRepo->getInscriptionData($lawyer['CodClie']);
  $solv = $lawRepo->getSolvency($lawyer['CodClie']);
  $operations = $opsRepo->operationsByClient($input, $year);
  $hasHistoric = $opsRepo->anyOperationHistoric($input);
  if ($year === null) {
    // Group descending by year (FechaE assumed YYYY-MM-DD). Limit each year to 25 entries for compactness.
    $byYear = [];
    foreach ($operations as $op) {
      $y = substr((string)($op['FechaE'] ?? ''), 0, 4);
      if (!preg_match('/^\d{4}$/', $y)) { $y = '----'; }
      if (!isset($byYear[$y])) { $byYear[$y] = []; }
      if (count($byYear[$y]) < 25) { // cap per year
        $byYear[$y][] = $op;
      }
    }
    // Sort years descending numerically, keeping non-year '----' at end.
    $years = array_keys($byYear);
    usort($years, function($a,$b){
      if ($a === '----') return 1; if ($b === '----') return -1;
      return (int)$b <=> (int)$a; // desc
    });
    foreach ($years as $y) { $groupedRecent[$y] = $byYear[$y]; }
  } else if ($year !== null && !$operations) {
    $hasAnyOtherYear = $opsRepo->anyOperationHistoric($input);
  }
} else {
  $notFound = true;
}

?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verifica las operaciones</title>
  <link rel="shortcut icon" href="favicon.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>.text-light{color:#f1f5f9}.sticky-bottom{position:fixed;bottom:1rem;right:1rem}</style>
</head>
<body>
<div class="min-h-screen bg-cover bg-center" style="background-image:url('piscina.jpg')">
  <div class="w-full flex justify-center items-center min-h-screen px-4 py-10">
      <div class="max-w-2xl w-full bg-black/50 backdrop-blur-sm rounded-lg p-6 space-y-4">
        <?php if ($notFound): ?>
          <div class="rounded bg-red-600/80 text-white px-4 py-3 text-sm font-semibold">Abogado no inscrito o identificación errónea, intente de nuevo</div>
          <div class="flex gap-3 mt-4">
            <form action="search.php" class="flex-1"><button class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-medium py-2 rounded">Realizar otra búsqueda</button></form>
            <form action="index.php"><button type="submit" class="bg-amber-500 hover:bg-amber-400 text-white font-medium py-2 px-4 rounded">Salir</button></form>
          </div>
        <?php elseif ($multiple && !$lawyer): ?>
          <div class="rounded bg-sky-600/80 text-white px-4 py-3 text-sm font-semibold">Se encontraron múltiples coincidencias, seleccione el abogado:</div>
          <ul class="mt-4 divide-y divide-slate-600/40">
            <?php foreach ($candidates as $cand): ?>
              <li class="flex items-center justify-between py-2 gap-4">
                <span class="text-slate-100 text-sm"><?= h($cand['CodClie'] ?? '') ?> | <?= h($cand['Clase'] ?? '') ?> | <?= h($cand['Descrip'] ?? '') ?></span>
                <a class="inline-block text-xs bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-3 py-1 rounded" href="system.php?input=<?= h(urlencode($input)) ?>&cod=<?= h(urlencode($cand['CodClie'] ?? '')) ?>">Ver</a>
              </li>
            <?php endforeach; ?>
          </ul>
          <div class="flex gap-3 mt-4">
            <a href="search.php" class="flex-1 text-center bg-emerald-600 hover:bg-emerald-500 text-white font-medium py-2 rounded">Nueva búsqueda</a>
            <form action="index.php"><button type="submit" class="bg-amber-500 hover:bg-amber-400 text-white font-medium py-2 px-4 rounded">Salir</button></form>
          </div>
        <?php else: ?>
          <h1 class="text-3xl font-bold text-white">Abogado inscrito!</h1>
          <div class="space-y-1 text-slate-100 text-sm mt-4">
            <?php if ($solv): ?>
              <p>Solvente hasta: <span class="font-semibold"><?= h($solv['hasta'] ?? '') ?></span></p>
            <?php else: ?><p>Solvencia no registrada</p><?php endif; ?>
            <p>CI: <span class="font-semibold"><?= h($lawyer['CodClie'] ?? '') ?></span></p>
            <p>Inpre: <span class="font-semibold"><?= h($lawyer['Clase'] ?? '') ?></span></p>
            <?php if ($insc): ?>
              <p>Fecha de Inscripción: <?= h($insc['Fecha'] ?? '') ?></p>
              <p>Número de Inscripción: <?= h($insc['Numero'] ?? '') ?></p>
              <p>Folio: <?= h($insc['Folio'] ?? '') ?></p>
            <?php endif; ?>
            <p><?= h($lawyer['Descrip'] ?? '') ?></p>
            <?php if ($solv && !empty($solv['CarnetNum2'])): ?>
              <p>CarnetNum: <span class="font-semibold"><?= h($solv['CarnetNum2']) ?></span></p>
            <?php else: ?><p>No posee carnet de seguridad</p><?php endif; ?>
          </div>
          <div class="mt-6">
            <?php if ($year !== null): ?>
              <h2 class="text-xl text-white font-semibold">Operaciones en el año <?= h($year) ?>:</h2>
              <div class="mt-2 max-h-72 overflow-y-auto pr-2 space-y-1">
                <?php if ($operations): ?>
                  <?php foreach ($operations as $op): ?>
                    <p class="text-xs text-slate-200 leading-snug"><?= h($op['FechaE']) ?> <?= h($op['NumeroD']) ?> <?= h($op['OrdenC']) ?> <?= h(trim(($op['Notas1'] ?? '') . ' ' . ($op['Notas2'] ?? '') . ' ' . ($op['Notas3'] ?? '') . ' ' . ($op['Notas4'] ?? '') . ' ' . ($op['Notas5'] ?? '') . ' ' . ($op['Notas6'] ?? '') . ' ' . ($op['Notas7'] ?? ''))) ?></p>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="text-slate-300 text-sm">Sin transacciones en el año <?= h($year) ?>...</p>
                <?php endif; ?>
              </div>
            <?php else: ?>
              <h2 class="text-xl text-white font-semibold">Operaciones recientes (descendentes por año):</h2>
              <div class="mt-2 max-h-80 overflow-y-auto pr-2 space-y-4">
                <?php if ($groupedRecent): ?>
                  <?php foreach ($groupedRecent as $y => $ops): ?>
                    <div>
                      <h3 class="text-sm font-semibold text-amber-300 mb-1">Año <?= h($y === '----' ? 'Desconocido' : $y) ?> (<?= count($ops) ?>)</h3>
                      <div class="space-y-1">
                        <?php foreach ($ops as $op): ?>
                          <p class="text-xs text-slate-200 leading-snug"><?= h($op['FechaE']) ?> <?= h($op['NumeroD']) ?> <?= h($op['OrdenC']) ?> <?= h(trim(($op['Notas1'] ?? '') . ' ' . ($op['Notas2'] ?? '') . ' ' . ($op['Notas3'] ?? '') . ' ' . ($op['Notas4'] ?? '') . ' ' . ($op['Notas5'] ?? '') . ' ' . ($op['Notas6'] ?? '') . ' ' . ($op['Notas7'] ?? ''))) ?></p>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="text-slate-300 text-sm">Sin transacciones recientes...</p>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="flex gap-3 mt-6 flex-wrap">
            <?php if ($hasHistoric): ?>
              <a href="all.php?ci=<?= h(urlencode($input)) ?>&ip=0" class="bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium px-4 py-2 rounded">Todas las operaciones</a>
            <?php endif; ?>
            <a href="search.php" class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-4 py-2 rounded">Realizar otra búsqueda</a>
            <form action="index.php"><button type="submit" class="bg-amber-500 hover:bg-amber-400 text-white text-sm font-medium px-4 py-2 rounded">Salir</button></form>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <div class="sticky-bottom">
    <a href="soport.php" class="block"><img src="contact2.png" alt="Soporte" width="100" height="100" class="hover:scale-105 transition" /></a>
  </div>
</div>
</body>
</html>