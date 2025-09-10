<?php
require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/layout.php';
require_auth();
if (!verify_csrf()) { render_header('Token inválido','domo.jpg'); echo alert_box('danger','Token CSRF inválido.'); render_footer(); exit; }
if (empty($_SESSION['delete_flow'])) { redirect('delete.php'); }
render_header('Confirmar Eliminación', 'domo.jpg');
?>
<div class="container">
  <div class="row min-vh-100 justify-content-center align-items-center">
    <div class="col-lg-5 col-md-7 col-sm-10">
      <div class="card shadow border-danger">
        <div class="card-body">
          <h1 class="h5 mb-3 text-danger">Confirma tu contraseña</h1>
          <form action="delete3.php" method="post" class="needs-validation" novalidate>
            <?= csrf_input(); ?>
            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <input name="contra" type="password" class="form-control" required minlength="8">
            </div>
            <div class="mb-3">
              <label class="form-label">Confirmar contraseña</label>
              <input name="contra2" type="password" class="form-control" required minlength="8">
            </div>
            <button class="btn btn-outline-danger w-100" type="submit">Eliminar Cuenta</button>
          </form>
          <div class="mt-3 text-center">
            <a href="pageuser.php" class="btn btn-secondary">Cancelar</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php render_footer(); ?>