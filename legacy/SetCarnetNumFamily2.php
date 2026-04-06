<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
render_header('Resultado - Carnet Familiar', 'piscina.jpg');

$ci = trim($_POST['ci'] ?? '');
$abgCi = trim($_POST['AbgCi'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$numeroD = trim($_POST['NumeroD'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$carnet = trim($_POST['CarnetNum'] ?? '');
$carnet2 = trim($_POST['CarnetNum2'] ?? '');

function fam_alert($type, $msg): string {
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
      if ($ci === '' || $abgCi === '' || $carnet === '' || $nombre === '' || $direccion === '' || $numeroD === '') {
          echo fam_alert('danger', 'Es necesario que rellene todos los campos.');
          echo "<form action='SetCarnetNumFamily.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
      } elseif ($carnet !== $carnet2) {
          echo fam_alert('danger', 'El número de carnet no coincide con su confirmación.');
          echo "<form action='SetCarnetNumFamily.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
      } else {
          try {
              $pdo = db();
              // 1. Comprobar si ya existe un registro para el familiar (CodClie exacto y activo)
              $stmt = $pdo->prepare("SELECT TOP 1 CarnetNum2 FROM SOLV WHERE CodClie LIKE ? AND Status = 1");
              $stmt->execute(['%' . $ci . '%']);
              $existingFamilia = $stmt->fetch(PDO::FETCH_ASSOC);

              // 2. Comprobar carnet duplicado
              $stmt = $pdo->prepare("SELECT TOP 1 1 FROM SOLV WHERE CarnetNum2 = ? AND Status = 1");
              $stmt->execute([$carnet]);
              $duplicado = (bool)$stmt->fetchColumn();

              // 3. Verificar existencia del abogado y solvencia del abogado
              $stmt = $pdo->prepare("SELECT TOP 1 1 FROM SACLIE WHERE CodClie LIKE ?");
              $stmt->execute(['%' . $abgCi . '%']);
              $abogadoExiste = (bool)$stmt->fetchColumn();

              $stmt = $pdo->prepare("SELECT TOP 1 1 FROM SOLV WHERE CodClie LIKE ? AND Status = 1");
              $stmt->execute(['%' . $abgCi . '%']);
              $abogadoSolvente = (bool)$stmt->fetchColumn();

              if (!$abogadoExiste) {
                  echo fam_alert('danger', 'La cédula del abogado es incorrecta o no está registrada.');
                  echo "<form action='SetCarnetNumFamily.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
              } elseif (!$abogadoSolvente) {
                  echo fam_alert('danger', 'No se encuentra registrada la solvencia del abogado.');
                  echo "<form action='SetCarnetNumFamily.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
              } elseif ($duplicado && (!$existingFamilia || ($existingFamilia && $existingFamilia['CarnetNum2'] !== $carnet))) {
                  echo fam_alert('danger', 'Ya existe una persona con ese número de carnet.');
                  echo "<form action='SetCarnetNumFamily.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
              } else {
                  if ($existingFamilia) {
                      // Actualizar solo el campo CarnetNum2 si ya existía (comportamiento similar al original update branch)
                      $upd = $pdo->prepare("UPDATE SOLV SET CarnetNum2 = ? WHERE CodClie LIKE ? AND Status = 1");
                      $ok = $upd->execute([$carnet, '%' . $ci . '%']);
                      if (!$ok || !$upd->rowCount()) {
                          echo fam_alert('danger', 'Ha ocurrido un error al actualizar, intente de nuevo.');
                          echo "<form action='SetCarnetNumFamily.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intentar de nuevo</button></form>";
                      } else {
                          echo fam_alert('success', 'Carnet actualizado correctamente para CI: ' . h($ci));
                          echo "<form action='SetCarnetNumFamily.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-5 py-2.5 transition'>Continuar</button></form>";
                      }
                  } else {
                      // Insertar nuevo registro familiar
                      $ins = $pdo->prepare("INSERT INTO SOLV (CodClie, Status, CarnetNum, CarnetNum2, Name, Direccion, NumeroD) VALUES (?, 1, ?, ?, ?, ?, ?)");
                      $ok = $ins->execute([$ci, $abgCi, $carnet, $nombre, $direccion, $numeroD]);
                      if (!$ok) {
                          echo fam_alert('danger', 'Ha ocurrido un error al insertar, intente de nuevo.');
                          echo "<form action='SetCarnetNumFamily.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 transition'>Intente de nuevo</button></form>";
                      } else {
                          echo fam_alert('success', 'Carnet almacenado correctamente para CI: ' . h($ci));
                          echo "<form action='SetCarnetNumFamily.php' class='pt-4'><button type='submit' class='w-full inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-5 py-2.5 transition'>Continuar</button></form>";
                      }
                  }
              }
          } catch (Throwable $e) {
              error_log('[SetCarnetNumFamily2] ' . $e->getMessage());
              echo fam_alert('danger', 'Error interno al procesar la solicitud.');
          }
      }
      ?>
      <div class="pt-6 flex flex-wrap gap-3">
        <a href="index.php" class="inline-flex justify-center rounded-md bg-neutral-700/70 hover:bg-neutral-600 text-neutral-100 text-sm font-medium px-5 py-2 transition focus:outline-none focus:ring focus:ring-neutral-400/40">Inicio</a>
        <a href="SetCarnetNum.php" class="inline-flex justify-center rounded-md bg-sky-600 hover:bg-sky-500 text-white text-sm font-medium px-5 py-2 transition focus:outline-none focus:ring focus:ring-sky-400/40">Carnet Abogado</a>
      </div>
    </div>
  </div>
</div>
<?php render_footer(); ?>