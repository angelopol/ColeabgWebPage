<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';

if (empty($_SESSION['correo']) || empty($_SESSION['pass']) || empty($_SESSION['cod'])) {
  header('Location: reccontra.php');
  exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/PHPMailer.php';
require_once __DIR__ . '/Exception.php';
require_once __DIR__ . '/SMTP.php';

$error = null; $sent = false;
if (!verify_csrf()) {
  http_response_code(422);
  $error = 'Token CSRF inválido.';
}

$cod = trim($_POST['cod'] ?? '');
if (!$error && $cod === '') { $error = 'Debe indicar el código.'; }

if (!$error) {
  if (hash_equals($_SESSION['cod'], $cod)) {
    try {
      $mail = new PHPMailer(true);
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'coleabgca@gmail.com';
      $mail->Password = 'nqqirendnyzasbyf';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port = 465;
      $mail->CharSet = 'UTF-8';
      $mail->setFrom('coleabgca@gmail.com', 'Colegio de Abogados del Estado Carabobo');
      $mail->addAddress($_SESSION['correo']);
      $mail->isHTML(true);
      $mail->Subject = 'Datos de ingreso al sistema del Colegio de Abogados del Estado Carabobo';
      $mail->Body = '<h1>Sus datos de usuario son:</h1><br><b>Contraseña: ' . h($_SESSION['pass']) . '</b><br><p><em>Si tiene algun problema contacte a informaticacolegioabogados@gmail.com</em></p>';
      $mail->AltBody = 'Contraseña: ' . $_SESSION['pass'];
      $mail->send();
      $sent = true;
    } catch (Throwable $e) {
      error_log('[reccontra3 mail] ' . $e->getMessage());
      $error = 'No se pudo enviar el email con sus datos.';
    }
  } else {
    $error = 'El código de confirmación no coincide.';
  }
}

render_header('Recuperar Contraseña - Envío', 'piscina.jpg');
?>
<div class="min-h-screen px-4 py-10">
  <div class="max-w-xl mx-auto">
    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl shadow p-8 space-y-6">
      <?php if($error): ?>
        <div class="rounded-md px-4 py-3 text-sm bg-rose-500/15 text-rose-200 border border-rose-400/30"><?php echo h($error); ?></div>
      <?php else: ?>
        <div class="rounded-md px-4 py-3 text-sm bg-emerald-500/15 text-emerald-200 border border-emerald-400/30">Email enviado a: <?php echo h($_SESSION['correo']); ?></div>
        <div class="rounded-md px-4 py-3 text-sm bg-indigo-500/15 text-indigo-200 border border-indigo-400/30">Para cambiar sus datos, <a href="ingre.php" class="underline">ingrese</a> al sistema y use el menú principal.</div>
        <?php session_destroy(); ?>
      <?php endif; ?>
      <div class="flex items-center gap-3 pt-2">
        <a href="reccontra.php" class="flex-1 inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-2.5">Volver</a>
        <a href="index.php" class="inline-flex justify-center rounded-md bg-amber-600 hover:bg-amber-500 text-white font-medium px-6 py-2.5">Salir</a>
      </div>
    </div>
  </div>
</div>
<?php render_footer(); ?>