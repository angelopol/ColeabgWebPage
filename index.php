<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/nav.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Colegio de Abogados del Estado Carabobo</title>
  <link rel="shortcut icon" href="favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div style="background: url('entrada.jpg') no-repeat center center fixed;background-size:cover;">
  <?php render_nav(); ?>
  <div class="container">
    <div class="row vh-100 justify-content-center align-items-center">
      <div class="col-auto p-5">
        <h1 class="display-1 text-white text-center"><strong>COLEGIO DE ABOGADOS DEL</strong></h1>
        <h1 class="display-1 text-white text-center"><strong>ESTADO CARABOBO</strong></h1>
      </div>
    </div>
  </div>
  <div class="sticky-bottom">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4">
          <div class="alert alert-light alert-dismissible fade show" role="alert">
            <strong>¿Necesitas ayuda?, presiona el botón de abajo para contactar con el soporte</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        </div>
      </div>
    </div>
    <a class="img-fluid" href="soport.php">
      <img src="contact2.png" alt="Soporte" width="100" height="100">
    </a>
  </div>
</div>
</body>
</html>