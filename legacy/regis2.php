<?php
require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/Repositories.php';
require __DIR__ . '/src/layout.php';
$emailAttempt = $_POST['correo'] ?? '';
if (!verify_csrf()) { log_security_event('csrf_register_fail', ['email' => $emailAttempt]); redirect('regis.php'); }

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'Exception.php';
require 'SMTP.php';

$ci = $_POST['ci'] ?? '';
$ip = $_POST['ip'] ?? '';
$correo = $_POST['correo'] ?? '';
$correo2 = $_POST['correo2'] ?? '';
$contra = $_POST['contra'] ?? '';
$contra2 = $_POST['contra2'] ?? '';

if ($ci === '' || $ip === '' || $correo === '' || $contra === '') {
    redirect('index.php');
}

$lawRepo = new LawyerRepository(db());
$userRepo = new UserRepository(db());

// Try to find lawyer by cedula (CodClie or ID3) and by Inpre (Clase)
$byCedula = $lawRepo->findByCedula($ci) ?? $lawRepo->findById3($ci);
$byInpre  = $lawRepo->findByInpre($ip);

render_header('Registrarse', 'salonestudiosjuridicos.jpg');
echo "<div class='container'><div class='row vh-100 justify-content-center align-items-center'><div class='col-auto p-5'>";

if (!$byInpre || !$byCedula || ($byInpre['CodClie'] !== $byCedula['CodClie'])) {
    echo alert('danger', 'Cédula e Inpre no coinciden o abogado no inscrito.');
    back_links();
    render_footer();
    exit;
}

$codClie = $byCedula['CodClie'];
$clase  = $byCedula['Clase'];

// Checks for duplicates
if ($userRepo->codClieExists($codClie) || $userRepo->claseExists($ip)) {
    echo alert('danger', 'Usuario ya registrado.');
    back_links();
    render_footer();
    exit;
}

if ($userRepo->emailExists($correo)) {
    echo alert('danger', 'Correo electrónico ya registrado.');
    back_links();
    render_footer();
    exit;
}

if ($correo !== $correo2) {
    echo alert('danger', 'Los correos electrónicos no coinciden.');
    back_links();
    render_footer();
    exit;
}
if ($contra !== $contra2) {
    echo alert('danger', 'Las contraseñas no coinciden.');
    back_links();
    render_footer();
    exit;
}

// Create user with hashed password (backwards compatible login still works for legacy rows)
if (!$userRepo->createUserHashed($correo, $contra, $clase, $codClie)) {
    echo alert('danger', 'Ha ocurrido un error al crear el usuario.');
    back_links();
    render_footer();
    exit;
}

// Attempt to send email
try {
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email inválido');
    }
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = getenv('SMTP_USER') ?: 'example@example.com';
    $mail->Password = getenv('SMTP_PASS') ?: 'password';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->setFrom('coleabgca@gmail.com', 'Colegio de Abogados del Estado Carabobo');
    $mail->addAddress($correo, $ci);
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Bienvenido al Colegio de Abogados del Estado Carabobo!';
    $mail->Body = '<h1>Sus datos de usuario son:</h1><br><b>Usuario: ' . h($correo) . '</b><br><b>Contraseña: ' . h($contra) . '</b><br><p><em>Si tiene algún problema contacte a informaticacolegioabogados@gmail.com</em></p>';
    $mail->AltBody = 'Usuario: ' . $correo . ' Contraseña: ' . $contra;
    $mail->send();
    echo alert('info', 'Email con sus datos enviado al correo proporcionado.');
} catch (Exception $e) {
    echo alert('danger', 'Error al enviar el email con los datos de usuario.');
}

echo alert('success', 'Usuario creado de forma exitosa, ya puede ingresar al sistema.');
echo "<form action='ingre.php'><input type='submit' class='btn btn-success w-100' value='Ingresar'></form><br>";
back_links();

echo "</div></div></div>";
render_footer();

function alert(string $type, string $message): string {
    return "<div class='alert alert-" . h($type) . "' role='alert'>" . h($message) . "</div>";
}

function back_links(): void {
    echo "<form action='regis.php' class='mt-2'><button type='submit' class='btn btn-primary'>Intente de nuevo</button></form><br><form action='index.php'><button type='submit' class='btn btn-warning'>Salir</button></form>";
}
?>