<?php
// Modernized Tailwind version
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
render_header('Asignar Carnet', 'piscina.jpg');
?>
<div class="min-h-screen px-4 py-12">
    <div class="max-w-xl mx-auto">
        <div class="mb-8 text-center">
            <h1 class="text-2xl md:text-3xl font-semibold text-white tracking-tight">Asignar Número de Carnet</h1>
            <p class="text-neutral-300 text-sm mt-2">Ingrese la cédula y el número de carnet (10 dígitos).</p>
        </div>
        <form action="SetCarnetNum2.php" method="post" class="space-y-6 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 shadow">
            <?= csrf_input(); ?>
            <div>
                <label class="block text-sm font-medium text-neutral-200 mb-1">Cédula</label>
                <input name="ci" type="text" required class="w-full rounded-md bg-neutral-800/60 border border-neutral-600/60 px-4 py-2.5 text-neutral-100 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400" placeholder="Ej: 12345678">
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-200 mb-1">Número de Carnet</label>
                    <input name="number" type="text" minlength="10" maxlength="10" required class="w-full rounded-md bg-neutral-800/60 border border-neutral-600/60 px-4 py-2.5 text-neutral-100 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400" placeholder="10 dígitos">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-200 mb-1">Confirmar Número</label>
                    <input name="number2" type="text" minlength="10" maxlength="10" required class="w-full rounded-md bg-neutral-800/60 border border-neutral-600/60 px-4 py-2.5 text-neutral-100 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400" placeholder="Repita el número">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 pt-2">
                <button type="submit" class="flex-1 inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-5 py-2.5 transition focus:outline-none focus:ring focus:ring-emerald-400/50">Registrar</button>
                <a href="solv.php" class="flex-1 inline-flex justify-center rounded-md bg-sky-600 hover:bg-sky-500 text-white font-medium px-5 py-2.5 transition focus:outline-none focus:ring focus:ring-sky-400/50">Registrar Solvencia</a>
            </div>
            <div class="pt-2 text-center">
                <a href="HomeFailed.php" class="inline-flex justify-center rounded-md bg-neutral-700/70 hover:bg-neutral-600 text-neutral-100 text-sm font-medium px-5 py-2 transition focus:outline-none focus:ring focus:ring-neutral-400/40">Volver</a>
            </div>
        </form>
    </div>
</div>
<?php render_footer(); ?>