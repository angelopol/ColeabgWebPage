<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
require_auth();
render_header('Buscar Operaciones', 'piscina.jpg');
?>
<div class="min-h-screen px-4 py-10">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl shadow p-8 space-y-10">
            <div>
                <h2 class="text-xl font-semibold text-white tracking-tight text-center">Buscar por Cédula (CodClie)</h2>
                <form action="SearchCodClie.php" method="post" class="mt-4 space-y-4">
                    <?php echo csrf_input(); ?>
                    <input name="ci" required class="w-full rounded-md bg-white/10 border border-white/20 focus:border-emerald-400 focus:ring-emerald-400/40 text-white px-4 py-2.5" placeholder="V12345678" />
                    <button class="w-full rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-6 py-2.5">Buscar</button>
                </form>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white tracking-tight text-center">Buscar por NumeroD</h2>
                <form action="SearchNumeroD.php" method="post" class="mt-4 space-y-4">
                    <?php echo csrf_input(); ?>
                    <input name="NumeroD" type="number" required class="w-full rounded-md bg-white/10 border border-white/20 focus:border-emerald-400 focus:ring-emerald-400/40 text-white px-4 py-2.5" placeholder="12345" />
                    <button class="w-full rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-6 py-2.5">Buscar</button>
                </form>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white tracking-tight text-center">Buscar por Rango de Fecha (hasta)</h2>
                <form action="SearchRange.php" method="post" class="mt-4 space-y-4">
                    <?php echo csrf_input(); ?>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-white/80 mb-1">Desde (YYYY-MM-DD)</label>
                            <input name="desde" required pattern="\\d{4}-\\d{2}-\\d{2}" class="w-full rounded-md bg-white/10 border border-white/20 focus:border-emerald-400 focus:ring-emerald-400/40 text-white px-4 py-2.5" placeholder="2025-01-01" />
                        </div>
                        <div>
                            <label class="block text-sm text-white/80 mb-1">Hasta (YYYY-MM-DD)</label>
                            <input name="hasta" required pattern="\\d{4}-\\d{2}-\\d{2}" class="w-full rounded-md bg-white/10 border border-white/20 focus:border-emerald-400 focus:ring-emerald-400/40 text-white px-4 py-2.5" placeholder="2025-12-31" />
                        </div>
                    </div>
                    <button class="w-full rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-6 py-2.5">Buscar</button>
                </form>
            </div>
            <div class="pt-2 text-center">
                <a href="HomeFailed.php" class="inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-2.5">Volver</a>
            </div>
        </div>
    </div>
</div>
<?php render_footer(); ?>