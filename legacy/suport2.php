<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
require_auth(false); // public but we can rate-limit by IP later

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer.php';
require_once __DIR__ . '/Exception.php';
require_once __DIR__ . '/SMTP.php';

$ci = trim($_POST['ci'] ?? '');
$ip = trim($_POST['ip'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$email = trim($_POST['email'] ?? '');

$errors = [];
if (!verify_csrf()) { $errors[] = 'Token CSRF inválido.'; }
if ($ci === '' || $ip === '' || $nombre === '' || $mensaje === '') { $errors[] = 'Todos los campos son obligatorios.'; }
if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Email inválido.'; }
if ($telefono && !preg_match('/^\d{10}$/', $telefono)) { $errors[] = 'Teléfono debe tener 10 dígitos.'; }
if ($mensaje !== '' && mb_strlen($mensaje) < 8) { $errors[] = 'El mensaje debe tener al menos 8 caracteres.'; }

$sent = false; $info = '';
if (!$errors) {
        try {
                $pdo = db();
                // Validate lawyer relationship by Inpre (Clase) and CI (CodClie/ID3)
                $stmt = $pdo->prepare("SELECT TOP 1 CodClie FROM SACLIE WHERE Clase = ?");
                $stmt->execute([$ip]);
                $rowIp = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$rowIp) {
                        $errors[] = 'Abogado no inscrito o datos incorrectos.';
                } else {
                        $stmt1 = $pdo->prepare("SELECT TOP 1 CodClie FROM SACLIE WHERE CodClie LIKE ?");
                        $stmt2 = $pdo->prepare("SELECT TOP 1 CodClie FROM SACLIE WHERE ID3 LIKE ?");
                        $like = "%$ci%"; $stmt1->execute([$like]); $stmt2->execute([$like]);
                        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC); $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                        if (!$row1 && !$row2) {
                                $errors[] = 'Abogado no inscrito o datos de identidad ingresados de forma incorrecta.';
                        } else {
                                $codCandidates = array_filter([$row1['CodClie'] ?? null, $row2['CodClie'] ?? null]);
                                if (!in_array($rowIp['CodClie'], $codCandidates, true)) {
                                        $errors[] = 'Cédula e Inpre no coinciden.';
                                }
                        }
                }
        } catch (Throwable $e) {
                error_log('[suport2 validate] ' . $e->getMessage());
                $errors[] = 'Error interno de validación.';
        }
}

if (!$errors) {
        try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'coleabgca@gmail.com';
                $mail->Password = 'nqqirendnyzasbyf'; // TODO: move to env/secret
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;
                $mail->CharSet = 'UTF-8';
                $mail->setFrom('coleabgca@gmail.com', 'Colegio de Abogados del Estado Carabobo');
                $mail->addAddress('informaticacolegioabogados@gmail.com');
                if ($email) { $mail->addAddress($email); }
                $mail->isHTML(true);
                $mail->Subject = 'Contacto con el Soporte';
                $bodyEsc = nl2br(h($mensaje));
                $mail->Body = '<h1>Mensaje</h1><p>' . $bodyEsc . '</p><hr><p><strong>Datos del usuario:</strong><br>' .
                        h($nombre) . '<br>' . h($email) . '<br>' . h($telefono) . '<br>Inpre: ' . h($ip) . '<br>Cédula: ' . h($ci) . '</p>';
                $mail->AltBody = 'Mensaje: ' . $mensaje . ' | Usuario: ' . $nombre . ' ' . $email . ' ' . $telefono . ' Inpre: ' . $ip . ' CI: ' . $ci;
                $mail->send();
                $sent = true; $info = 'Mensaje enviado de forma exitosa.';
        } catch (Throwable $e) {
                error_log('[suport2 mail] ' . $e->getMessage());
                $errors[] = 'Ha ocurrido un error al enviar el mensaje.';
        }
}

render_header('Contacto Soporte', 'canchabeisbol.jpg');
?>
<div class="min-h-screen px-4 py-10">
    <div class="max-w-xl mx-auto">
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl shadow p-8 space-y-6">
            <h1 class="text-xl font-semibold text-white tracking-tight">Resultado del envío</h1>
            <?php if($errors): ?>
                <div class="space-y-3">
                    <?php foreach($errors as $e): ?>
                        <div class="rounded-md px-4 py-2 text-sm bg-rose-500/15 text-rose-200 border border-rose-400/30"><?php echo h($e); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="rounded-md px-4 py-3 text-sm font-medium bg-emerald-500/15 text-emerald-200 border border-emerald-400/30"><?php echo h($info); ?></div>
            <?php endif; ?>
            <div class="flex items-center gap-3 pt-2">
                <a href="soport.php" class="flex-1 inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-2.5 transition">Volver</a>
                <a href="index.php" class="inline-flex justify-center rounded-md bg-amber-600 hover:bg-amber-500 text-white font-medium px-6 py-2.5 transition">Salir</a>
            </div>
        </div>
    </div>
</div>
<?php render_footer(); ?>