<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/Repositories.php';
require_once __DIR__ . '/src/layout.php';

$ci = trim($_GET['ci'] ?? '');
$ip = (int)($_GET['ip'] ?? 0);
if ($ci === '') redirect('index.php');

$repo = new OperationsRepository(db());
$rows = $repo->allOperations($ci);

render_header('Verifica las operaciones', 'domo.jpg');
?>
<div class="container">
  <div class="row min-vh-100 justify-content-center align-items-center">
    <div class="col-auto p-5">
      <p class="h2 text-light">Operaciones:</p><br>
      <?php foreach ($rows as $row): ?>
        <p class="h2 text-light"><?= h($row['FechaE']) ?></p><br>
        <p class="text-light"><?= h($row['NumeroD']) ?> <?= h($row['OrdenC']) ?> <?= h(trim(($row['Notas1'] ?? '') . ' ' . ($row['Notas2'] ?? '') . ' ' . ($row['Notas3'] ?? '') . ' ' . ($row['Notas4'] ?? '') . ' ' . ($row['Notas5'] ?? '') . ' ' . ($row['Notas6'] ?? '') . ' ' . ($row['Notas7'] ?? ''))) ?></p>
      <?php endforeach; ?>
      <br>
      <?php if ($ip === 1): ?>
        <p class="text-light">¿No logra conseguir sus operaciones?, intente buscando en <a href="allmore.php?ci=<?= h(urlencode($ci)) ?>&ip=1">Ver más</a></p>
        <form action="search2.php"><input type="submit" class="btn btn-success w-100" value="Realizar otra búsqueda"></form>
      <?php elseif ($ip === 0): ?>
        <p class="text-light">¿No logra conseguir sus operaciones?, intente buscando en <a href="allmore.php?ci=<?= h(urlencode($ci)) ?>&ip=0">Ver más</a></p>
        <form action="search.php"><input type="submit" class="btn btn-success w-100" value="Realizar otra búsqueda"></form>
      <?php elseif ($ip === 2): ?>
        <p class="text-light">¿No logra conseguir sus operaciones?, intente buscando en <a href="allmore.php?ci=<?= h(urlencode($ci)) ?>&ip=2">Ver más</a></p>
        <form action="pageuser.php"><input type="submit" class="btn btn-success w-100" value="Regresar"></form>
      <?php endif; ?>
      <br>
      <form action="index.php"><button type="submit" class="btn btn-warning">Salir</button></form>
    </div>
  </div>
</div>
<?php render_footer(); ?>