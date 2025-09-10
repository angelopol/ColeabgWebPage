<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/Repositories.php';

$input = trim($_REQUEST['input'] ?? ''); // Inpre
$yearRaw = trim($_REQUEST['input2'] ?? '');
$year = valid_year_or_null($yearRaw);
if ($input === '') redirect('index.php');

$db = db();
$lawRepo = new LawyerRepository($db);
$opsRepo = new OperationsRepository($db);

$lawyer = $lawRepo->findByInpre($input);
$notFound = !$lawyer;
$insc = $solv = null; $operations = []; $hasHistoric = false;
if ($lawyer) {
  $insc = $lawRepo->getInscriptionData($lawyer['CodClie']);
  $solv = $lawRepo->getSolvency($lawyer['CodClie']);
  $operations = $opsRepo->operationsByInpre($lawyer['CodClie'], $year);
  $hasHistoric = $opsRepo->anyOperationHistoric($lawyer['CodClie']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verifica las operaciones</title>
  <link rel="shortcut icon" href="favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div style="background: url('salonestudiosjuridicos.jpg') no-repeat center center fixed;background-size:cover;">
  <div class="container">
    <div class="row min-vh-100 justify-content-center align-items-center">
      <div class="col-auto p-5">
        <?php if ($notFound): ?>
          <div class="alert alert-danger" role="alert">Abogado no inscrito o identificación errónea, intente de nuevo</div>
          <br>
          <form action="search2.php"><input type="submit" class="btn btn-success w-100" value="Realizar otra búsqueda"></form>
          <br>
          <form action="index.php"><button type="submit" class="btn btn-warning">Salir</button></form>
        <?php else: ?>
          <p class="h1 text-light">Abogado inscrito!</p><br>
          <?php if ($solv): ?><p class="text-light">Solvente hasta: <?= h($solv['hasta'] ?? '') ?></p><br><?php else: ?><p class="text-light">Solvencia no registrada</p><br><?php endif; ?>
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
              <a href="all.php?ci=<?= h(urlencode($lawyer['CodClie'])) ?>&ip=1" class="btn btn-success">Todas las operaciones</a>
            <?php endif; ?>
            <a href="search2.php" class="btn btn-success">Realizar otra búsqueda</a>
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