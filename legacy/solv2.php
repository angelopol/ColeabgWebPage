<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
render_header('Resultado - Solvencia', 'piscina.jpg');

$ci = trim($_POST['ci'] ?? '');
$hasta = trim($_POST['hasta'] ?? '');
$numeroD = trim($_POST['NumeroD'] ?? '');
$carnet = trim($_POST['CarnetNum'] ?? '');
$carnet2 = trim($_POST['CarnetNum2'] ?? '');

function solv_alert($type, $msg): string {
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
            if ($ci === '' || $hasta === '' || $numeroD === '') {
                    echo solv_alert('danger', 'Es necesario que rellene todos los campos obligatorios.');
                    echo "<form action='solv.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
            } elseif ($carnet !== '' && $carnet !== $carnet2) {
                    echo solv_alert('danger', 'El número de carnet no coincide con su confirmación.');
                    echo "<form action='solv.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
            } else {
                    try {
                            $pdo = db();
                            // Comprobar si ya existe solvencia activa exacta (CodClie + Status)
                            $stmt = $pdo->prepare("SELECT TOP 1 1 FROM SOLV WHERE CodClie LIKE ? AND Status = 1");
                            $stmt->execute(['%' . $ci . '%']);
                            $yaExiste = (bool)$stmt->fetchColumn();

                            if ($yaExiste) {
                                    echo solv_alert('danger', "Ya existe un registro activo. Puede <a href='SetCarnetNum.php' class='underline decoration-dotted'>asignar un carnet</a> si falta.");
                                    echo "<form action='solv.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
                            } else {
                                    // Si trae carnet verificar duplicado
                                    if ($carnet !== '') {
                                            $stmt = $pdo->prepare("SELECT TOP 1 1 FROM SOLV WHERE CarnetNum2 = ? AND Status = 1");
                                            $stmt->execute([$carnet]);
                                            if ($stmt->fetchColumn()) {
                                                    echo solv_alert('danger', 'Ya existe una persona con ese número de carnet.');
                                                    echo "<form action='solv.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
                                                    echo "<div class='pt-4 text-xs text-neutral-300'>Sugerencia: verifique antes en Asignar Carnet.</div>";
                                                    goto endblock;
                                            }
                                    }
                                    $sql = "INSERT INTO SOLV (CodClie, hasta, Status, NumeroD, CarnetNum2) VALUES (?, ?, 1, ?, ?)";
                                    $stmt = $pdo->prepare($sql);
                                    $ok = $stmt->execute([$ci, $hasta, $numeroD, $carnet !== '' ? $carnet : null]);
                                    if (!$ok) {
                                            echo solv_alert('danger', 'Ha ocurrido un error al insertar, intente de nuevo.');
                                            echo "<form action='solv.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
                                    } else {
                                            echo solv_alert('success', 'Solvencia de CI: ' . h($ci) . ' hasta ' . h($hasta) . ' almacenada correctamente.');
                                            echo "<form action='solv.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-5 py-2.5 transition'>Registrar otra</button></form>";
                                    }
                            }
                    } catch (Throwable $e) {
                            error_log('[solv2] ' . $e->getMessage());
                            echo solv_alert('danger', 'Error interno al procesar la solicitud.');
                    }
            }
            endblock:;
            ?>
            <div class="pt-6 flex flex-wrap gap-3">
                <a href="index.php" class="inline-flex justify-center rounded-md bg-neutral-700/70 hover:bg-neutral-600 text-neutral-100 text-sm font-medium px-5 py-2 transition focus:outline-none focus:ring focus:ring-neutral-400/40">Inicio</a>
                <a href="SetCarnetNum.php" class="inline-flex justify-center rounded-md bg-sky-600 hover:bg-sky-500 text-white text-sm font-medium px-5 py-2 transition focus:outline-none focus:ring focus:ring-sky-400/40">Asignar Carnet</a>
            </div>
        </div>
    </div>
</div>
<?php render_footer(); ?>