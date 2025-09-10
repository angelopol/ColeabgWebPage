<?php
require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/layout.php';
require_auth();
// token to ensure user passed through confirmation flow
$_SESSION['delete_flow'] = time();
render_header('Eliminar Usuario', 'domo.jpg');
?>
<div class="container">
  <div class="row min-vh-100 justify-content-center align-items-center">
    <div class="col-lg-6 col-md-8 col-sm-10">
      <div class="card shadow border-danger">
        <div class="card-body text-center">
          <h1 class="h4 mb-4 text-danger">¿Seguro que deseas eliminar tu usuario?</h1>
          <p class="text-muted">Esta acción es permanente y eliminará tu cuenta de acceso.</p>
          <form action="delete2.php" method="post" class="d-grid gap-2">
            <?= csrf_input(); ?>
            <button class="btn btn-outline-danger" type="submit">Sí, continuar</button>
          </form>
          <div class="mt-3">
            <a href="pageuser.php" class="btn btn-secondary">Cancelar</a>
          </div>
        </div>
      </div>
      <div class="mt-4 text-center">
        <a href="soport.php"><img src="contact2.png" width="80" alt="Soporte"></a>
      </div>
    </div>
  </div>
</div>
<?php render_footer(); ?>