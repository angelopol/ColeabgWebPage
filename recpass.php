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
<div class="container">
  <div class="row min-vh-100 justify-content-center align-items-center">
    <div class="col-auto p-5">
      <p class='h1 text-light'>Cambia tu Contraseña</p>
      <form action="recpass2.php" method="post" class='mt-3'>
        <?= csrf_input(); ?>
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text">Contraseña</span>
          <input name="contra" type="password" class="form-control" placeholder="Antigua contraseña" pattern=".{8,}" required>
          <input name="contra2" type="password" class="form-control" placeholder="Confirmar" pattern=".{8,}" required>
        </div>
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text">Nueva Contraseña</span>
          <input name="contranew" type="password" class="form-control" placeholder="Mínimo 8 caracteres" pattern=".{8,}" required>
          <input name="contranew2" type="password" class="form-control" placeholder="Confirmar" pattern=".{8,}" required>
        </div>
        <input type="submit" class="btn btn-success w-100" value="Cambiar">
      </form>
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