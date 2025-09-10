<?php
require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/Repositories.php';
require __DIR__ . '/src/layout.php';
require __DIR__ . '/src/nav.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'Exception.php';
require 'SMTP.php';

if (empty($_SESSION['nameuser'])) { redirect('index.php'); }

if (!verify_csrf()) { render_header('Error','domo.jpg'); echo alert_box('danger','Token CSRF inválido.'); render_footer(); exit; }
$contra = $_POST['contra'] ?? '';
$contra2 = $_POST['contra2'] ?? '';
$contranew = $_POST['contranew'] ?? '';
$contranew2 = $_POST['contranew2'] ?? '';

render_header('Cambiar Contraseña', 'domo.jpg');
render_auth_nav('Menu');
echo "<div class='container'><div class='row vh-100 justify-content-center align-items-center'><div class='col-auto p-5'>";

if ($contranew === '' || $contra === '') {
    session_destroy(); redirect('index.php');
}

if ($contra !== $contra2) { echo alert_box('danger','La contraseña antigua no coincide con su confirmación.'); end_links(); finish(); }

$userRepo = new UserRepository(db());
$user = $userRepo->findByEmail($_SESSION['nameuser']);
if (!$user) { session_destroy(); redirect('index.php'); }

// Verify old password (supports hash or plain)
$validOld = false;
if (isset($user['pass']) && str_starts_with((string)$user['pass'], '$2y$')) {
    $validOld = password_verify($contra, $user['pass']);
} else {
    $validOld = ($contra === $user['pass']);
}
if (!$validOld) { echo alert_box('danger','La contraseña antigua es incorrecta.'); end_links(); finish(); }

if ($contranew !== $contranew2) { echo alert_box('danger','La contraseña nueva no coincide con su confirmación.'); end_links(); finish(); }

if (!$userRepo->updatePasswordHashed($_SESSION['nameuser'], $contranew)) {
    echo alert_box('danger','Ha ocurrido un error al cambiar la contraseña.'); end_links(); finish();
}

// Send notification email (best effort)
try {
    if (filter_var($_SESSION['nameuser'], FILTER_VALIDATE_EMAIL)) {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USER') ?: 'example@example.com';
        $mail->Password = getenv('SMTP_PASS') ?: 'password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->setFrom($mail->Username, 'Colegio de Abogados del Estado Carabobo');
        $mail->addAddress($_SESSION['nameuser']);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Contraseña actualizada';
        $mail->Body = '<h1>Su contraseña ha sido cambiada</h1><p>Si no reconoce esta acción contacte soporte inmediatamente.</p>';
        $mail->AltBody = 'Su contraseña ha sido cambiada. Si no reconoce esta acción contacte soporte.';
        $mail->send();
    }
} catch (Exception $e) { /* ignore */ }

echo alert_box('success','Contraseña cambiada exitosamente.');
echo "<form action='ingre.php' class='mt-3'><input type='submit' class='btn btn-light w-100' value='Inicio'></form>";
end_links();
session_destroy();

finish();

function end_links(): void {
    echo "<br><form action='index.php'><button type='submit' class='btn btn-warning'>Salir</button></form>";
}
function finish(): void { echo "</div></div></div>"; render_footer(); exit; }
?>