<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
render_header('Actualizar Solvencia', 'piscina.jpg');
?>
<div class="min-h-screen px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8 text-center">
            <h1 class="text-2xl md:text-3xl font-semibold text-white tracking-tight">Actualizar / Registrar Nueva Factura</h1>
            <p class="text-neutral-300 text-sm mt-2">Crea un nuevo registro de solvencia posterior al último existente.</p>
        </div>
        <form action="UpdateOperations2.php" method="post" class="space-y-8 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 shadow">
            <?= csrf_input(); ?>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-200 mb-1">Cédula</label>
                    <input name="ci" type="text" required class="w-full rounded-md bg-neutral-800/60 border border-neutral-600/60 px-4 py-2.5 text-neutral-100 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400" placeholder="CI">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-200 mb-1">Solvente hasta</label>
                    <input name="hasta" type="text" required class="w-full rounded-md bg-neutral-800/60 border border-neutral-600/60 px-4 py-2.5 text-neutral-100 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400" placeholder="AAAA-MM-DD o texto">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-200 mb-1">Fecha de emisión (FechaE)</label>
                    <input name="FechaE" type="text" required class="w-full rounded-md bg-neutral-800/60 border border-neutral-600/60 px-4 py-2.5 text-neutral-100 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400" placeholder="AAAA-MM-DD">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-200 mb-1">Número de factura (NumeroD)</label>
                    <input name="NumeroD" type="text" required class="w-full rounded-md bg-neutral-800/60 border border-neutral-600/60 px-4 py-2.5 text-neutral-100 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400" placeholder="Factura">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 pt-2">
                <button type="submit" class="flex-1 inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-5 py-2.5 transition focus:outline-none focus:ring focus:ring-emerald-400/50">Registrar</button>
                <a href="solv.php" class="flex-1 inline-flex justify-center rounded-md bg-sky-600 hover:bg-sky-500 text-white font-medium px-5 py-2.5 transition focus:outline-none focus:ring focus:ring-sky-400/50">Crear Solvencia Inicial</a>
            </div>
            <div class="pt-2 text-center">
                <a href="soport.php" class="inline-flex justify-center rounded-md bg-neutral-700/70 hover:bg-neutral-600 text-neutral-100 text-sm font-medium px-5 py-2 transition focus:outline-none focus:ring focus:ring-neutral-400/40">
                    Soporte
                </a>
            </div>
        </form>
    </div>
</div>
<?php render_footer(); ?>