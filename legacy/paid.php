<?php
require_once __DIR__ . '/src/layout.php';
require_auth();
render_header('Registrar Pago', 'domo.jpg');
?>

<div class="container mx-auto min-h-screen py-10">
    <div class="max-w-xl mx-auto p-6 bg-slate-900/70 rounded-lg border border-slate-700/50">
        <h1 class="text-2xl text-white font-semibold mb-6">Datos del pago</h1>
        <form action="paid2.php" method="post" enctype="multipart/form-data" class="space-y-4">
            <?php require_once __DIR__ . '/src/bootstrap.php'; echo csrf_input(); ?>
            <div>
                <label class="block text-slate-200 mb-1">Monto</label>
                <input type="text" class="w-full rounded bg-slate-800/70 border border-slate-700 px-3 py-2 text-white" name="monto" placeholder="Ej: 15.00" />
            </div>
            <div>
                <label class="block text-slate-200 mb-1">Periodo del pago</label>
                <input type="text" class="w-full rounded bg-slate-800/70 border border-slate-700 px-3 py-2 text-white" name="periodo" placeholder="Ej: 2025-01" />
            </div>
            <div>
                <label class="block text-slate-200 mb-1">Número de referencia</label>
                <input type="number" class="w-full rounded bg-slate-800/70 border border-slate-700 px-3 py-2 text-white" name="ref" required />
            </div>
            <div>
                <label class="block text-slate-200 mb-1">Comprobante del pago (captura)</label>
                <input type="file" class="w-full rounded bg-slate-800/70 border border-slate-700 px-3 py-2 text-white" name="fileToUpload" required />
            </div>
            <div>
                <label class="block text-slate-200 mb-1">Nota</label>
                <textarea class="w-full rounded bg-slate-800/70 border border-slate-700 px-3 py-2 text-white" name="nota" rows="3" placeholder="Información adicional"></textarea>
            </div>
            <div class="pt-2">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-4 py-2 rounded w-full">Enviar</button>
            </div>
        </form>
    </div>
</div>

<?php render_footer(); ?>
