<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
render_header('Resultado - Actualizar Solvencia', 'piscina.jpg');

$ci = trim($_POST['ci'] ?? '');
$hasta = trim($_POST['hasta'] ?? '');
$fechaE = trim($_POST['FechaE'] ?? '');
$numeroD = trim($_POST['NumeroD'] ?? '');

function upd_alert($type, $msg): string {
    $colors = [
        'danger' => 'bg-rose-500/15 text-rose-200 border border-rose-400/30',
        'success' => 'bg-emerald-500/15 text-emerald-200 border border-emerald-400/30',
        'info' => 'bg-sky-500/15 text-sky-200 border border-sky-400/30'
    ];
    $class = $colors[$type] ?? $colors['info'];
    return "<div class='rounded-lg px-4 py-3 text-sm font-medium {$class}'>{$msg}</div>";
}
?>
<div class="min-h-screen px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 shadow space-y-6">
            <h1 class="text-xl font-semibold text-white tracking-tight">Resultado</h1>
            <?php
            if ($ci === '' || $hasta === '' || $fechaE === '' || $numeroD === '') {
                    echo upd_alert('danger', 'Es necesario que rellene todos los campos.');
                    echo "<form action='UpdateOperations.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Volver</button></form>";
            } else {
                    try {
                            $pdo = db();
                            // Obtener última solvencia existente
                            $stmt = $pdo->prepare("SELECT TOP 1 FechaE, NumeroD FROM SOLV WHERE CodClie LIKE ? AND Status = 1 ORDER BY FechaE DESC");
                            $stmt->execute(['%' . $ci . '%']);
                            $ultima = $stmt->fetch(PDO::FETCH_ASSOC);
                            if (!$ultima) {
                                    echo upd_alert('danger', 'No existe solvencia inicial, cree una primero.');
                                    echo "<form action='solv.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-sky-600 hover:bg-sky-500 text-white font-medium px-5 py-2.5 transition'>Crear Solvencia</button></form>";
                            } else {
                                    $fechaUltima = $ultima['FechaE'];
                                    $numeroDUltimo = $ultima['NumeroD'];
                                    // Validar fecha (asumiendo formato comparable lexicográficamente YYYY-MM-DD)
                                    if ($fechaUltima !== null && $fechaUltima > $fechaE) {
                                            echo upd_alert('danger', 'La fecha ingresada es anterior a la última registrada.');
                                            echo "<form action='UpdateOperations.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Volver</button></form>";
                                    } elseif ($numeroDUltimo == $numeroD) {
                                            echo upd_alert('danger', 'El número de factura ya está registrado.');
                                            echo "<form action='UpdateOperations.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Volver</button></form>";
                                    } else {
                                            // Insertar nuevo registro como nueva solvencia (manteniendo patrón original de crecimiento)
                                            $ins = $pdo->prepare("INSERT INTO SOLV (CodClie, hasta, Status, NumeroD, CarnetNum2, FechaE) VALUES (?, ?, 1, ?, NULL, ?)");
                                            $ok = $ins->execute([$ci, $hasta, $numeroD, $fechaE]);
                                            if (!$ok) {
                                                    echo upd_alert('danger', 'Error al registrar la nueva solvencia.');
                                                    echo "<form action='UpdateOperations.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Volver</button></form>";
                                            } else {
                                                    echo upd_alert('success', 'Nueva solvencia registrada correctamente.');
                                                    echo "<form action='UpdateOperations.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-5 py-2.5 transition'>Registrar otra</button></form>";
                                            }
                                    }
                            }
                    } catch (Throwable $e) {
                            error_log('[UpdateOperations2] ' . $e->getMessage());
                            echo upd_alert('danger', 'Error interno al procesar la solicitud.');
                    }
            }
            ?>
            <div class="pt-6 flex flex-wrap gap-3">
                <a href="index.php" class="inline-flex justify-center rounded-md bg-neutral-700/70 hover:bg-neutral-600 text-neutral-100 text-sm font-medium px-5 py-2 transition focus:outline-none focus:ring focus:ring-neutral-400/40">Inicio</a>
                <a href="solv.php" class="inline-flex justify-center rounded-md bg-sky-600 hover:bg-sky-500 text-white text-sm font-medium px-5 py-2 transition focus:outline-none focus:ring focus:ring-sky-400/40">Crear Solvencia</a>
            </div>
        </div>
    </div>
</div>
<?php render_footer(); ?>