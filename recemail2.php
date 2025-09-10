<?php
require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/Repositories.php';
require __DIR__ . '/src/layout.php';
require __DIR__ . '/src/nav.php';
require_auth();

if (!verify_csrf()) { echo alert_box('danger','Token CSRF inválido.'); render_footer(); exit; }
$contra = $_POST['contra'] ?? '';
$contra2 = $_POST['contra2'] ?? '';
$correo = $_POST['correo'] ?? '';
$correo2 = $_POST['correo2'] ?? '';

render_header('Cambiar Email', 'domo.jpg');
render_auth_nav('Menu');
echo "<div class='container'><div class='row vh-100 justify-content-center align-items-center'><div class='col-auto p-5'>";

if ($correo === '' || $contra === '') { session_destroy(); redirect('index.php'); }
if ($contra !== $contra2) { echo alert_box('danger','La contraseña no coincide con su confirmación.'); exit_links(); finish(); }

$userRepo = new UserRepository(db());
$user = $userRepo->findByEmail($_SESSION['nameuser']);
if (!$user) { session_destroy(); redirect('index.php'); }

// verify password (hash or plain)
$validPass = false;
if (isset($user['pass']) && str_starts_with((string)$user['pass'], '$2y$')) {
    $validPass = password_verify($contra, $user['pass']);
} else { $validPass = ($contra === $user['pass']); }
if (!$validPass) { echo alert_box('danger','Contraseña incorrecta.'); exit_links(); finish(); }

if ($correo !== $correo2) { echo alert_box('danger','El email nuevo no coincide con su confirmación.'); exit_links(); finish(); }
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) { echo alert_box('danger','Email inválido.'); exit_links(); finish(); }

// If new email exists already
if ($userRepo->emailExists($correo)) { echo alert_box('danger','Email ya registrado.'); exit_links(); finish(); }

if (!$userRepo->updateEmail($_SESSION['nameuser'], $correo)) {
    echo alert_box('danger','Error al actualizar el email.'); exit_links(); finish();
}

send_email($_SESSION['nameuser'], 'Email actualizado', '<p>Su email de acceso ha sido cambiado.</p>');
send_email($correo, 'Bienvenido (email actualizado)', '<p>Su nuevo email ha quedado registrado.</p>');

echo alert_box('success','Email cambiado exitosamente.');
echo "<form action='ingre.php' class='mt-3'><input type='submit' class='btn btn-light w-100' value='Inicio'></form>";
exit_links();
session_destroy();
finish();

function exit_links(): void { echo "<br><form action='index.php'><button type='submit' class='btn btn-warning'>Salir</button></form>"; }
function finish(): void { echo "</div></div></div>"; render_footer(); exit; }
?>