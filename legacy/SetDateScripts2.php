<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
require_auth();

// Basic validation + CSRF
if (!verify_csrf()) {
        http_response_code(422);
        $error = 'Token CSRF inválido. Intente nuevamente.';
}

$scriptName = trim($_POST['ScriptName'] ?? '');
$date = trim($_POST['date'] ?? '');
$days = trim($_POST['days'] ?? '');

if (!isset($error)) {
        if ($scriptName === '' || $date === '' || $days === '') {
                $error = 'Todos los campos son obligatorios.';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $error = 'Formato de fecha inválido (use YYYY-MM-DD).';
        } elseif (!ctype_digit($days)) {
                $error = 'Días debe ser numérico.';
        }
}

$success = null; $action = null; $detail = null;
if (!isset($error)) {
        try {
                $pdo = db();
                $pdo->beginTransaction();
                $stmt = $pdo->prepare('SELECT TOP 1 ScriptName FROM DateScripts WHERE ScriptName = ?');
                $stmt->execute([$scriptName]);
                $exists = (bool)$stmt->fetchColumn();
                if ($exists) {
                        $upd = $pdo->prepare('UPDATE DateScripts SET date = ?, days = ? WHERE ScriptName = ?');
                        $upd->execute([$date, $days, $scriptName]);
                        $action = 'updated';
                } else {
                        $ins = $pdo->prepare('INSERT INTO DateScripts (ScriptName, date, days) VALUES (?,?,?)');
                        $ins->execute([$scriptName, $date, $days]);
                        $action = 'created';
                }
                $pdo->commit();
                $success = true;
                $detail = $action === 'updated' ? 'actualizada' : 'registrada';
        } catch (Throwable $e) {
                if ($pdo && $pdo->inTransaction()) { $pdo->rollBack(); }
                error_log('[SetDateScripts2] ' . $e->getMessage());
                $error = 'Ha ocurrido un error interno.';
        }
}

render_header('Resultado - Date Scripts', 'piscina.jpg');
?>
<div class="min-h-screen px-4 py-10">
    <div class="max-w-xl mx-auto">
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl shadow p-8 space-y-6">
            <h1 class="text-xl font-semibold text-white tracking-tight">Resultado</h1>
            <?php if(isset($error)): ?>
                <div class="rounded-lg px-4 py-3 text-sm font-medium bg-rose-500/15 text-rose-200 border border-rose-400/30">
                    <?php echo h($error); ?>
                </div>
            <?php else: ?>
                <div class="rounded-lg px-4 py-3 text-sm font-medium bg-emerald-500/15 text-emerald-200 border border-emerald-400/30">
                    Fecha de <strong><?php echo h($scriptName); ?></strong> (<?php echo h($date); ?>) <?php echo $detail; ?> correctamente.
                </div>
            <?php endif; ?>
            <div class="flex items-center gap-3 pt-2">
                <a href="SetDateScripts.php" class="flex-1 inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-2.5 transition focus:outline-none focus:ring focus:ring-indigo-400/50">Volver</a>
                <a href="HomeFailed.php" class="inline-flex justify-center rounded-md bg-slate-600 hover:bg-slate-500 text-white font-medium px-6 py-2.5 transition focus:outline-none focus:ring focus:ring-slate-400/50">Inicio</a>
            </div>
        </div>
    </div>
</div>
<?php render_footer(); ?>