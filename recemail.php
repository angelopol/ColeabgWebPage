<?php
require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/layout.php';
require __DIR__ . '/src/Repositories.php';
require __DIR__ . '/src/nav.php';
require_auth();
render_header('Cambiar Email', 'domo.jpg');
render_auth_nav('Menu');
?>
<div class="container">
  <div class="row min-vh-100 justify-content-center align-items-center">
    <div class="col-auto p-5">
      <p class='h1 text-light'>Cambia tu Email</p>
      <div class='alert alert-info'>Email actual: <?= h($_SESSION['nameuser']) ?></div>
      <form action="recemail2.php" method="post">
        <?= csrf_input(); ?>
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text">Nuevo Email</span>
          <input name="correo" type="email" class="form-control" placeholder="nombre@ejemplo.com" required>
          <input name="correo2" type="email" class="form-control" placeholder="Confirmar" required>
        </div>
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text">Contraseña</span>
          <input name="contra" type="password" class="form-control" pattern=".{8,}" required>
          <input name="contra2" type="password" class="form-control" placeholder="Confirmar" pattern=".{8,}" required>
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