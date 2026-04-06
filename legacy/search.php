<?php
require_once __DIR__ . '/src/layout.php';
render_header('Verifica las operaciones', 'piscina.jpg');
?>
<div class="w-full flex justify-center items-center min-h-screen px-4">
    <div class="w-full max-w-md bg-slate-900/70 backdrop-blur rounded-xl p-8 shadow-lg space-y-8">
        <form action="system.php" method="post" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-1">Cédula</label>
                <input type="number" name="input" max="99999999" required class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 text-slate-100 px-3 py-2 text-sm outline-none" />
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-1">Año (opcional)</label>
                <input type="number" name="input2" min="2000" max="2030" class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 text-slate-100 px-3 py-2 text-sm outline-none" />
            </div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-sm rounded-md px-4 py-2 transition">Buscar</button>
        </form>
        <div class="flex flex-col gap-3">
            <form action="search2.php">
                <button class="w-full border border-emerald-600 text-emerald-400 hover:bg-emerald-600 hover:text-white font-medium text-sm rounded-md px-4 py-2 transition">Buscar por Inpre</button>
            </form>
            <form action="index.php" class="space-y-2">
                <p class="text-xs text-slate-300">Ej: 12345678, 2023</p>
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-white font-medium text-sm rounded-md px-4 py-2 transition">Salir</button>
            </form>
        </div>
    </div>
    <div class="sticky-bottom">
        <a href="soport.php" class="block"><img src="contact2.png" width="100" height="100" class="hover:scale-105 transition" alt="Soporte" /></a>
    </div>
</div>
<?php render_footer(); ?>