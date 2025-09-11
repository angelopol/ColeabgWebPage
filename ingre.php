<!-- ANGELO POLGROSSI | 04124856320 -->
<?php

session_start();

if (empty($_SESSION["nameuser"])) {
} else {
    header("Location: pageuser.php");
    
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingresar</title>
    <link rel="shortcut icon" href="favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      .text-light{color:#f1f5f9}.sticky-bottom{position:fixed;bottom:1rem;right:1rem}
    </style>
    
</head>
<body>
    <div class="min-h-screen bg-cover bg-center flex items-center justify-center px-4" style="background-image:url('domo.jpg')">
      <div class="w-full max-w-md bg-slate-900/70 backdrop-blur rounded-xl p-8 shadow-lg">
        <h1 class="text-2xl font-bold text-white mb-6">Ingresa al Sistema</h1>
        <form action="login.php" method="post" class="space-y-5">
          <?php require_once __DIR__ . '/src/bootstrap.php'; echo csrf_input(); ?>
          <div>
            <label class="block text-sm font-medium text-slate-200 mb-1">Usuario o Email</label>
            <input name="user" type="text" required class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/40 text-slate-100 px-3 py-2 text-sm outline-none" placeholder="usuario o email" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-200 mb-1">Contraseña</label>
            <input name="password" type="password" required class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/40 text-slate-100 px-3 py-2 text-sm outline-none" placeholder="********" />
          </div>
          <input type="hidden" name="recaptcha_response" id="recaptchaResponse" />
          <button type="submit" class="w-full inline-flex justify-center items-center bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-sm rounded-md px-4 py-2 transition">Ingresar</button>
        </form>
        <div class="mt-6 space-y-2 text-xs text-slate-300">
          <p>Olvidaste tus datos? <a class="text-emerald-400 hover:text-emerald-300 underline" href="reccontra.php">Recuperar</a></p>
          <p>¿Aún no tienes una cuenta? <a class="text-emerald-400 hover:text-emerald-300 underline" href="regis.php">Regístrate</a></p>
        </div>
        <form action="index.php" class="mt-6">
          <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-white font-medium text-sm rounded-md px-4 py-2 transition">Salir</button>
        </form>
      </div>
      <div class="sticky-bottom">
        <a href="soport.php" class="block"><img src="contact2.png" alt="Soporte" width="100" height="100" class="hover:scale-105 transition" /></a>
      </div>
    </div>
</body>
</html>