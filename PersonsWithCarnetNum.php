<?php
require_once __DIR__ . '/src/layout.php';
require_auth();
render_header('Personas con Carnet', 'piscina.jpg');

$pdo = DataConnect();

// Total de registros con CarnetNum2 válido
$total = (int)$pdo->query("SELECT COUNT(*) AS c FROM SOLV WHERE Status = 1 AND (CarnetNum2 IS NOT NULL AND CarnetNum2 <> 'None')")->fetch(PDO::FETCH_ASSOC)['c'];

// Últimos 20 por NumeroD descendente
$st = $pdo->query("SELECT TOP 20 CodClie, FechaE, NumeroD, Solved, CarnetNum2, hasta FROM SOLV WHERE Status = 1 AND (CarnetNum2 IS NOT NULL AND CarnetNum2 <> 'None') ORDER BY NumeroD DESC");
$rows = $st->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto min-h-screen py-10">
    <div class="max-w-5xl mx-auto p-6 bg-slate-900/70 rounded-lg border border-slate-700/50">
        <h1 class="text-2xl text-white font-semibold mb-2">Personas con Carnet</h1>
        <p class="text-slate-300 mb-6">Número de resultados: <span class="font-semibold"><?= h($total) ?></span></p>

        <?php if (!$rows): ?>
            <div class="rounded-md px-4 py-3 text-sm bg-rose-500/15 text-rose-200 border border-rose-400/30">No se encontraron registros.</div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($rows as $r): ?>
                    <?php
                        $ordenC = 'not found';
                        $nota = 'not found';
                        $st2 = $pdo->prepare("SELECT TOP 1 OrdenC, Notas1, Notas2, Notas3, Notas4, Notas5, Notas6, Notas7 FROM SAFACT WHERE NumeroD = ?");
                        $st2->execute([$r['NumeroD']]);
                        if ($o = $st2->fetch(PDO::FETCH_ASSOC)) {
                                $ordenC = (string)($o['OrdenC'] ?? '');
                                $nota = trim(($o['Notas1'] ?? '') . ' ' . ($o['Notas2'] ?? '') . ' ' . ($o['Notas3'] ?? '') . ' ' . ($o['Notas4'] ?? '') . ' ' . ($o['Notas5'] ?? '') . ' ' . ($o['Notas6'] ?? '') . ' ' . ($o['Notas7'] ?? ''));
                                if ($nota === '') { $nota = 'not found'; }
                        }
                    ?>
                    <div class="rounded-md px-4 py-3 text-sm bg-amber-500/15 text-amber-200 border border-amber-400/30">
                        <div><span class="text-white/60">CI:</span> <?= h($r['CodClie']) ?></div>
                        <div><span class="text-white/60">Fecha:</span> <?= h($r['FechaE']) ?></div>
                        <div><span class="text-white/60">Factura:</span> <?= h($r['NumeroD']) ?></div>
                        <div><span class="text-white/60">OrdenC:</span> <?= h($ordenC) ?></div>
                        <div><span class="text-white/60">Resuelto:</span> <?= h($r['Solved']) ?></div>
                        <div><span class="text-white/60">CarnetNum:</span> <?= h($r['CarnetNum2']) ?></div>
                        <div><span class="text-white/60">Hasta:</span> <?= h($r['hasta']) ?></div>
                        <div class="mt-2 text-white/80"><span class="text-white/60">Nota:</span> <?= h($nota) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="mt-6">
            <a class="inline-block bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-4 py-2 rounded" href="HomeFailed.php">Volver</a>
        </div>
    </div>
</div>

<?php render_footer(); ?>
