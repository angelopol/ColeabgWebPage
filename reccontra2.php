<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';

if (empty($_SESSION['reccontra'])) { header('Location: reccontra.php'); exit; }
unset($_SESSION['reccontra']);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/PHPMailer.php';
require_once __DIR__ . '/Exception.php';
require_once __DIR__ . '/SMTP.php';

$ci = trim($_POST['ci'] ?? '');
$ip = trim($_POST['ip'] ?? '');
$correo = trim($_POST['correo'] ?? '');

$errors = [];
if (!verify_csrf()) { $errors[] = 'Token CSRF inválido.'; }
if ($ci === '' || $ip === '' || $correo === '') { $errors[] = 'Todos los campos son obligatorios.'; }
if ($correo && !filter_var($correo, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Email inválido.'; }

$info = '';
if (!$errors) {
        try {
                $pdo = db();
                // Validate user identity against USUARIOS table
                $stmtIp = $pdo->prepare('SELECT TOP 1 CodClie, pass FROM USUARIOS WHERE Clase = ?');
                $stmtIp->execute([$ip]);
                $rowIp = $stmtIp->fetch(PDO::FETCH_ASSOC);

                $stmtCi = $pdo->prepare('SELECT TOP 1 CodClie, pass FROM USUARIOS WHERE CodClie LIKE ?');
                $stmtCi->execute(['%' . $ci . '%']);
                $rowCi = $stmtCi->fetch(PDO::FETCH_ASSOC);

                $stmtEmail = $pdo->prepare('SELECT TOP 1 CodClie, pass FROM USUARIOS WHERE email = ?');
                $stmtEmail->execute([$correo]);
                $rowEmail = $stmtEmail->fetch(PDO::FETCH_ASSOC);

                if ($rowIp && $rowCi && $rowEmail && $rowIp['CodClie'] === $rowCi['CodClie'] && $rowIp['CodClie'] === $rowEmail['CodClie']) {
                        $cod = bin2hex(random_bytes(10));
                        $_SESSION['cod'] = $cod;
                        $_SESSION['pass'] = $rowIp['pass'];
                        $_SESSION['correo'] = $correo;

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
                                $mail->addAddress($correo);
                                $mail->isHTML(true);
                                $mail->Subject = 'Codigo de Confirmacion';
                                $mail->Body = '<h1>El codigo para recuperar su contraseña es:</h1><br><strong>' . h($cod) . '</strong><br><p><em>Si tiene algun problema con la pagina web contacte a informaticacolegioabogados@gmail.com</em></p>';
                                $mail->AltBody = 'Codigo de confirmacion: ' . $cod;
                                $mail->send();
                                $info = 'Email con el código de confirmación enviado a: ' . h($correo);
                        } catch (Throwable $e) {
                                error_log('[reccontra2 mail] ' . $e->getMessage());
                                $errors[] = 'No se pudo enviar el email con el código.';
                        }
                } else {
                        $errors[] = 'Datos de usuario no coinciden.';
                }
        } catch (Throwable $e) {
                error_log('[reccontra2] ' . $e->getMessage());
                $errors[] = 'Error interno.';
        }
}

render_header('Recuperar Contraseña - Código', 'piscina.jpg');
?>
<div class="min-h-screen px-4 py-10">
    <div class="max-w-lg mx-auto">
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl shadow p-8 space-y-6">
            <?php if($errors): ?>
                <div class="space-y-3">
                    <?php foreach($errors as $e): ?>
                        <div class="rounded-md px-4 py-2 text-sm bg-rose-500/15 text-rose-200 border border-rose-400/30"><?php echo h($e); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="rounded-md px-4 py-3 text-sm bg-indigo-500/15 text-indigo-200 border border-indigo-400/30"><?php echo $info; ?></div>
                <h2 class="text-lg font-semibold text-white tracking-tight">Ingrese el código de confirmación</h2>
                <form action="reccontra3.php" method="post" class="space-y-4">
                    <?php echo csrf_input(); ?>
                    <input name="cod" required class="w-full rounded-md bg-white/10 border border-white/20 focus:border-emerald-400 focus:ring-emerald-400/40 text-white px-4 py-2.5" placeholder="Código" />
                    <button class="w-full inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-6 py-2.5">Confirmar</button>
                </form>
            <?php endif; ?>
            <div class="flex items-center gap-3 pt-2">
                <a href="reccontra.php" class="flex-1 inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-2.5">Volver</a>
                <a href="index.php" class="inline-flex justify-center rounded-md bg-amber-600 hover:bg-amber-500 text-white font-medium px-6 py-2.5">Salir</a>
            </div>
        </div>
    </div>
</div>
<?php render_footer(); ?>