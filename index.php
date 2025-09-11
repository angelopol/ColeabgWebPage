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
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors: { brand: '#0ea5e9' } } } };
  </script>
  <style>
    .sticky-bottom { position: fixed; bottom: 1rem; right: 1rem; }
  </style>
</head>
<body>
<div style="background: url('entrada.jpg') no-repeat center center fixed;background-size:cover;">
  <?php render_nav(); ?>
  <div class="w-full min-h-screen flex flex-col items-center justify-center backdrop-brightness-75 text-center px-4">
      <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white tracking-tight">COLEGIO DE ABOGADOS DEL</h1>
      <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white tracking-tight mt-2">ESTADO CARABOBO</h1>
      <p class="mt-8 max-w-xl text-slate-200 text-lg">Portal institucional para verificación de solvencias y operaciones.</p>
  </div>
  <div class="sticky-bottom">
      <div class="bg-white/90 shadow rounded p-4 mb-2 max-w-sm">
        <p class="text-sm text-slate-700 font-semibold">¿Necesitas ayuda? Pulsa el botón para contactar soporte.</p>
      </div>
      <a href="soport.php" class="block">
        <img src="contact2.png" alt="Soporte" width="100" height="100" class="hover:scale-105 transition" />
      </a>
  </div>
</div>
</body>
</html>