<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
require_auth();
if (!verify_csrf()) { http_response_code(422); $error = 'Token CSRF inválido.'; }

$ci = trim($_POST['ci'] ?? '');
$error = $error ?? null; $results = [];
if (!isset($error) && $ci === '') { $error = 'Debe indicar la cédula.'; }

if (!$error) {
        try {
                $pdo = db();
                $stmt = $pdo->prepare('SELECT CodClie, FechaE, Status, NumeroD, Solved, CarnetNum2, hasta FROM SOLV WHERE CodClie LIKE ?');
                $stmt->execute(['%' . $ci . '%']);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($rows) {
                        $stmt2 = $pdo->prepare('SELECT OrdenC, Notas1, Notas2, Notas3 FROM SAFACT WHERE NumeroD = ?');
                        foreach ($rows as $r) {
                                $stmt2->execute([$r['NumeroD']]);
                                $o = $stmt2->fetch(PDO::FETCH_ASSOC);
                                $ordenC = $o['OrdenC'] ?? 'not found';
                                $note = isset($o) ? trim(($o['Notas1'] ?? '') . ' ' . ($o['Notas2'] ?? '') . ' ' . ($o['Notas3'] ?? '')) : 'not found';
                                $results[] = [
                                        'CodClie' => $r['CodClie'],
                                        'FechaE' => $r['FechaE'],
                                        'Status' => $r['Status'],
                                        'NumeroD' => $r['NumeroD'],
                                        'OrdenC' => $ordenC,
                                        'Nota' => $note,
                                        'Solved' => $r['Solved'],
                                        'CarnetNum2' => $r['CarnetNum2'],
                                        'hasta' => $r['hasta'],
                                ];
                        }
                } else {
                        $error = 'No se encontraron resultados por cédula.';
                }
        } catch (Throwable $e) {
                error_log('[SearchCodClie] ' . $e->getMessage());
                $error = 'Error interno.';
        }
}

render_header('Resultados por Cédula', 'piscina.jpg');
?>
<div class="min-h-screen px-4 py-10">
    <div class="max-w-3xl mx-auto">
        <div class="space-y-4">
            <?php if($error): ?>
                <div class="rounded-md px-4 py-3 text-sm bg-rose-500/15 text-rose-200 border border-rose-400/30"><?php echo h($error); ?></div>
            <?php else: ?>
                <?php foreach($results as $item): ?>
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-5">
                        <div class="grid md:grid-cols-2 gap-2 text-white/90 text-sm">
                            <div><span class="text-white/60">CI:</span> <?php echo h($item['CodClie']); ?></div>
                            <div><span class="text-white/60">Fecha:</span> <?php echo h($item['FechaE']); ?></div>
                            <div><span class="text-white/60">Status:</span> <?php echo h($item['Status']); ?></div>
                            <div><span class="text-white/60">Factura:</span> <?php echo h($item['NumeroD']); ?></div>
                            <div><span class="text-white/60">OrdenC:</span> <?php echo h($item['OrdenC']); ?></div>
                            <div><span class="text-white/60">Resuelto:</span> <?php echo h($item['Solved']); ?></div>
                            <div><span class="text-white/60">CarnetNum:</span> <?php echo h($item['CarnetNum2']); ?></div>
                            <div><span class="text-white/60">Hasta:</span> <?php echo h($item['hasta']); ?></div>
                        </div>
                        <?php if(trim($item['Nota']) !== ''): ?>
                            <div class="mt-3 text-white/80 text-sm"><span class="text-white/60">Nota:</span> <?php echo h($item['Nota']); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="flex items-center gap-3 pt-2">
                <a href="SearchOperations.php" class="flex-1 inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-2.5">Volver</a>
                <a href="HomeFailed.php" class="inline-flex justify-center rounded-md bg-slate-600 hover:bg-slate-500 text-white font-medium px-6 py-2.5">Inicio</a>
            </div>
        </div>
    </div>
</div>
<?php render_footer(); ?>