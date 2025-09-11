<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
require_once __DIR__ . '/src/Repositories.php';
render_header('Resultado - Carnet', 'piscina.jpg');

$ci = trim($_POST['ci'] ?? '');
$number = trim($_POST['number'] ?? '');
$number2 = trim($_POST['number2'] ?? '');

function alert($type, $msg): string {
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
    <div class="max-w-xl mx-auto space-y-6">
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 shadow space-y-5">
            <h1 class="text-xl font-semibold text-white tracking-tight">Resultado</h1>
            <?php
            if ($ci === '' || $number === '') {
                    echo alert('danger', 'Es necesario que rellene todos los campos.');
                    echo "<form action='SetCarnetNum.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
            } else if ($number !== $number2) {
                    echo alert('danger', 'El número de carnet no coincide con su confirmación.');
                    echo "<form action='SetCarnetNum.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
            } else {
                          try {
                                  $pdo = db();
                                  // Verificar solvencia existente para el cliente
                                  $stmt = $pdo->prepare("SELECT TOP 1 1 FROM SOLV WHERE CodClie LIKE ? AND Status = 1");
                                  $stmt->execute(['%' . $ci . '%']);
                                  $hasSolv = (bool)$stmt->fetchColumn();
                                  if ($hasSolv) {
                                          // Verificar duplicado de carnet
                                          $stmt = $pdo->prepare("SELECT TOP 1 1 FROM SOLV WHERE CarnetNum2 = ? AND Status = 1");
                                          $stmt->execute([$number]);
                                          $duplicate = (bool)$stmt->fetchColumn();
                                          if ($duplicate) {
                                                  echo alert('danger', 'Ya existe una persona con ese número de carnet.');
                                                  echo "<form action='solv.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
                                          } else {
                                                  $upd = $pdo->prepare("UPDATE SOLV SET CarnetNum2 = ? WHERE CodClie LIKE ? AND Status = 1");
                                                  $ok = $upd->execute([$number, '%' . $ci . '%']);
                                                  if (!$ok || !$upd->rowCount()) {
                                                          echo alert('danger', 'Ha ocurrido un error, por favor intente de nuevo.');
                                                          echo "<form action='SetCarnetNum.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intentar de nuevo</button></form>";
                                                  } else {
                                                          echo alert('success', 'Carnet de CI: ' . h($ci) . ', Número: ' . h($number) . ' almacenado correctamente.');
                                                          echo "<form action='SetCarnetNum.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-5 py-2.5 transition'>Continuar</button></form>";
                                                  }
                                          }
                                  } else {
                                          echo alert('danger', "Aún no se ha registrado la solvencia de esta persona. <a href='solv.php' class='underline decoration-dotted'>Registre la solvencia</a>.");
                                          echo "<form action='solv.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Ir a registrar solvencia</button></form>";
                                  }
                          } catch (Throwable $e) {
                                  error_log('[SetCarnetNum2] ' . $e->getMessage());
                                  echo alert('danger', 'Error interno al procesar la solicitud.');
                          }
            }
            ?>
            <div class="pt-6 text-center">
                <a href="index.php" class="inline-flex justify-center rounded-md bg-neutral-700/70 hover:bg-neutral-600 text-neutral-100 text-sm font-medium px-5 py-2 transition focus:outline-none focus:ring focus:ring-neutral-400/40">Inicio</a>
            </div>
        </div>
    </div>
</div>
<?php render_footer(); ?>