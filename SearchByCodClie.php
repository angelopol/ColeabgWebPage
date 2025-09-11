<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
render_header('Buscar Abogado', 'piscina.jpg');
?>
<div class="min-h-screen px-4 py-10">
    <div class="max-w-xl mx-auto">
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl shadow p-8">
            <h1 class="text-2xl font-semibold text-white tracking-tight text-center">Buscar Abogado</h1>
            <form action="SearchByCodClie2.php" method="post" class="mt-6 space-y-5">
                <?php echo csrf_input(); ?>
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-1">Cédula</label>
                    <input name="input" required class="w-full rounded-md bg-white/10 border border-white/20 focus:border-emerald-400 focus:ring-emerald-400/40 text-white px-4 py-2.5 placeholder-white/40" placeholder="V12345678" />
                </div>
                <button class="w-full rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-6 py-2.5">Buscar</button>
            </form>
            <div class="flex items-center gap-3 mt-6">
                <a href="SearchByClase.php" class="flex-1 inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-2.5">Buscar por Inpre</a>
                <a href="index.php" class="inline-flex justify-center rounded-md bg-amber-600 hover:bg-amber-500 text-white font-medium px-6 py-2.5">Salir</a>
            </div>
        </div>
    </div>
</div>
<?php render_footer(); ?>