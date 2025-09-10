<?php
require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/layout.php';
require __DIR__ . '/src/Repositories.php';
require_auth();
if (empty($_SESSION['delete_flow'])) { redirect('delete.php'); }

if (!verify_csrf()) { render_header('Token inválido','domo.jpg'); echo alert_box('danger','Token CSRF inválido.'); render_footer(); exit; }
$contra = $_POST['contra'] ?? '';
$contra2 = $_POST['contra2'] ?? '';

render_header('Eliminar Usuario', 'domo.jpg');
echo "<div class='container'><div class='row vh-100 justify-content-center align-items-center'><div class='col-lg-6 col-md-8 col-sm-10'><div class='card shadow border-danger'><div class='card-body'>";

if ($contra === '' || $contra2 === '') { session_destroy(); redirect('index.php'); }
if ($contra !== $contra2) { echo alert_box('danger','La contraseña no coincide con su confirmación.'); back(); finish(); }

$userRepo = new UserRepository(db());
$user = $userRepo->findByEmail($_SESSION['nameuser']);
if (!$user) { session_destroy(); redirect('index.php'); }

// verify password (hash or plain)
$validPass = false;
if (isset($user['pass']) && str_starts_with((string)$user['pass'], '$2y$')) {
    $validPass = password_verify($contra, $user['pass']);
} else { $validPass = ($contra === $user['pass']); }
if (!$validPass) { echo alert_box('danger','Contraseña incorrecta.'); back(); finish(); }

if (!$userRepo->deleteByEmail($_SESSION['nameuser'])) { echo alert_box('danger','Error al eliminar el usuario.'); back(); finish(); }

send_email($_SESSION['nameuser'], 'Cuenta eliminada', '<p>Su cuenta ha sido eliminada.</p>');

echo alert_box('success','Su usuario ha sido eliminado exitosamente.');
echo "<form action='index.php' class='mt-3'><button type='submit' class='btn btn-warning w-100'>Salir</button></form>";
session_destroy();
finish();

function back(): void { echo "<form action='delete.php' class='mt-3'><button type='submit' class='btn btn-primary'>Intente de nuevo</button></form>"; }
function finish(): void { echo "</div></div></div></div></div>"; render_footer(); unset($_SESSION['delete_flow']); exit; }
?>