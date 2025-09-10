<?php
require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/Repositories.php';
require __DIR__ . '/src/layout.php';
require __DIR__ . '/src/nav.php';
require_auth();

$email = $_SESSION['nameuser'];
$userRepo = new UserRepository(db());
$lawRepo = new LawyerRepository(db());
$opsRepo = new OperationsRepository(db());
$user = $userRepo->findByEmail($email);
if (!$user) { redirect('logout.php'); }
$lawyer = $lawRepo->findByCedula($user['CodClie'] ?? '') ?? $lawRepo->findById3($user['CodClie'] ?? '');
$displayName = $lawyer['Descrip'] ?? 'Usuario';
$currentYear = (int)date('Y');
$recent = $opsRepo->operationsByClient($user['CodClie'], $currentYear);
render_header('Inicio', 'salonestudiosjuridicos.jpg');
render_auth_nav('Menu');
?>
<div class="container">
  <div class="row min-vh-100 justify-content-center align-items-center">
    <div class="col-auto p-5">
      <p class='h1 text-light'>¡Bienvenido <?= h($displayName) ?>!</p>
      <br><br>
      <?php if ($recent): ?>
        <p class='h2 text-light'>Operaciones recientes:</p><br>
        <?php foreach ($recent as $op): ?>
          <p class='text-light'><?= h($op['OrdenC'] . ' ' . ($op['Notas1'] ?? '') . ' ' . ($op['Notas2'] ?? '') . ' ' . ($op['Notas3'] ?? '') . ' ' . ($op['Notas4'] ?? '') . ' ' . ($op['Notas5'] ?? '') . ($op['Notas6'] ?? '') . ' ' . ($op['Notas7'] ?? '')) ?></p>
        <?php endforeach; ?>
      <?php else: ?>
        <p class='text-light'>Sin operaciones recientes...</p>
      <?php endif; ?>
      <br>
      <div>
        <a href='all.php?ci=<?= h($user['CodClie']) ?>&ip=2' class='btn btn-success'>Todas las operaciones</a>
      </div>
      <br>
      <div class='btn-group'>
        <a href='logout.php' class='btn btn-danger'>Cerrar sesión</a>
        <form action='index.php'>
          <button type='submit' class='btn btn-warning'>Salir</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php render_footer(); ?>