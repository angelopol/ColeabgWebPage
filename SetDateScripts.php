<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
require_auth();
render_header('Set Date Scripts', 'piscina.jpg');
?>
<div class="min-h-screen px-4 py-10">
    <div class="max-w-xl mx-auto">
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl shadow p-8">
            <h1 class="text-2xl font-semibold text-white tracking-tight mb-6">Registrar / Actualizar Fecha de Script</h1>
            <form action="SetDateScripts2.php" method="post" class="space-y-6">
                <?php echo csrf_input(); ?>
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-1">Nombre del Script</label>
                    <input name="ScriptName" required maxlength="100" class="w-full rounded-md bg-white/10 border border-white/20 focus:border-emerald-400 focus:ring-emerald-400/40 text-white px-4 py-2.5 placeholder-white/40" placeholder="Ej: job_diario" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-1">Fecha (YYYY-MM-DD)</label>
                    <input name="date" required pattern="\\d{4}-\\d{2}-\\d{2}" class="w-full rounded-md bg-white/10 border border-white/20 focus:border-emerald-400 focus:ring-emerald-400/40 text-white px-4 py-2.5 placeholder-white/40" placeholder="2025-12-31" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-1">Días</label>
                    <input name="days" required pattern="\\d+" class="w-full rounded-md bg-white/10 border border-white/20 focus:border-emerald-400 focus:ring-emerald-400/40 text-white px-4 py-2.5 placeholder-white/40" placeholder="30" />
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-6 py-2.5 transition focus:outline-none focus:ring focus:ring-emerald-400/50">Registrar</button>
                    <a href="HomeFailed.php" class="inline-flex justify-center rounded-md bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-2.5 transition focus:outline-none focus:ring focus:ring-indigo-400/50">Volver</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php render_footer(); ?>