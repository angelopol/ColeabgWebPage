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

$insc = null; $solv = null; $operations = []; $hasHistoric = false; $hasAnyOtherYear = false; $notFound = false;
if ($lawyer) {
  $insc = $lawRepo->getInscriptionData($lawyer['CodClie']);
  $solv = $lawRepo->getSolvency($lawyer['CodClie']);
  $operations = $opsRepo->operationsByClient($input, $year);
  $hasHistoric = $opsRepo->anyOperationHistoric($input);
  if ($year !== null && !$operations) {
    // Check if there are operations in other years
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
<div style="background: url('piscina.jpg') no-repeat center center fixed;background-size: cover;">
  <div class="container">
    <div class="row min-vh-100 justify-content-center align-items-center">
      <div class="col-auto p-5">
        <?php if ($notFound): ?>
          <div class="alert alert-danger" role="alert">Abogado no inscrito o identificación errónea, intente de nuevo</div>
          <br>
          <form action="search.php"><input type="submit" class="btn btn-success w-100" value="Realizar otra búsqueda"></form>
          <br>
          <form action="index.php"><button type="submit" class="btn btn-warning">Salir</button></form>
                <?php elseif ($multiple && !$lawyer): ?>
                  <div class="alert alert-info" role="alert">Se encontraron múltiples coincidencias, seleccione el abogado:</div>
                  <ul class="list-group mb-3">
                    <?php foreach ($candidates as $cand): ?>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><?= h($cand['CodClie'] ?? '') ?> | <?= h($cand['Clase'] ?? '') ?> | <?= h($cand['Descrip'] ?? '') ?></span>
                        <a class="btn btn-sm btn-primary" href="system.php?input=<?= h(urlencode($input)) ?>&cod=<?= h(urlencode($cand['CodClie'] ?? '')) ?>">Ver</a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                  <a href="search.php" class="btn btn-success">Nueva búsqueda</a>
                  <form class="mt-2" action="index.php"><button type="submit" class="btn btn-warning">Salir</button></form>
        <?php else: ?>
          <p class="h1 text-light">Abogado inscrito!</p><br>
          <?php if ($solv): ?>
            <p class="text-light">Solvente hasta: <?= h($solv['hasta'] ?? '') ?></p><br>
          <?php else: ?>
            <p class="text-light">Solvencia no registrada</p><br>
          <?php endif; ?>
          <p class="text-light">CI: <?= h($lawyer['CodClie'] ?? '') ?></p><br>
          <p class="text-light">Inpre: <?= h($lawyer['Clase'] ?? '') ?></p><br>
          <?php if ($insc): ?>
            <p class="text-light">Fecha de Inscripción: <?= h($insc['Fecha'] ?? '') ?></p><br>
            <p class="text-light">Número de Inscripción: <?= h($insc['Numero'] ?? '') ?></p><br>
            <p class="text-light">Folio: <?= h($insc['Folio'] ?? '') ?></p><br>
          <?php endif; ?>
          <p class="text-light"><?= h($lawyer['Descrip'] ?? '') ?></p><br>
          <?php if ($solv && !empty($solv['CarnetNum2'])): ?>
            <p class="text-light">CarnetNum: <?= h($solv['CarnetNum2']) ?></p><br>
          <?php else: ?>
            <p class="text-light">No posee carnet de seguridad</p><br>
          <?php endif; ?>

          <?php if ($year !== null): ?>
            <p class="h2 text-light">Operaciones en el año <?= h($year) ?>:</p><br>
          <?php else: ?>
            <p class="h2 text-light">Operaciones recientes:</p><br>
          <?php endif; ?>

          <?php if ($operations): ?>
            <?php foreach ($operations as $op): ?>
              <p class="text-light"><?= h($op['FechaE']) ?> <?= h($op['NumeroD']) ?> <?= h($op['OrdenC']) ?> <?= h(trim(($op['Notas1'] ?? '') . ' ' . ($op['Notas2'] ?? '') . ' ' . ($op['Notas3'] ?? '') . ' ' . ($op['Notas4'] ?? '') . ' ' . ($op['Notas5'] ?? '') . ' ' . ($op['Notas6'] ?? '') . ' ' . ($op['Notas7'] ?? ''))) ?></p>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-light">Sin transacciones <?= $year !== null ? 'en el año ' . h($year) : 'recientes' ?>...</p>
          <?php endif; ?>

          <br>
          <div class="btn-group">
            <?php if ($hasHistoric): ?>
              <a href="all.php?ci=<?= h(urlencode($input)) ?>&ip=0" class="btn btn-success">Todas las operaciones</a>
            <?php endif; ?>
            <a href="search.php" class="btn btn-success">Realizar otra búsqueda</a>
          </div>
          <br><br>
          <form action="index.php"><button type="submit" class="btn btn-warning">Salir</button></form>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="sticky-bottom">
    <a class="img-fluid" href="soport.php">
      <img src="contact2.png" alt="Soporte" width="100" height="100">
    </a>
  </div>
</div>
</body>
</html>