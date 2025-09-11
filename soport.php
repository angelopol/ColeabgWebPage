<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';
render_header('Contacta con el Soporte', 'canchabeisbol.jpg');
?>
<div class="min-h-screen px-4 py-10">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl shadow p-8">
            <h1 class="text-2xl font-semibold text-white tracking-tight mb-6">Contacta con el Soporte</h1>
            <p class="text-sm text-white/70 mb-6">Complete el formulario. Evite usar puntos o guiones para separar números.</p>
            <form action="suport2.php" method="post" class="space-y-6">
                <?php echo csrf_input(); ?>
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-1">Nombre y Apellido</label>
                    <input name="nombre" required maxlength="120" class="w-full rounded-md bg-white/10 border border-white/20 focus:border-indigo-400 focus:ring-indigo-400/40 text-white px-4 py-2.5 placeholder-white/40" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-1">Cédula</label>
                        <input name="ci" type="text" pattern="\d{5,11}" required class="w-full rounded-md bg-white/10 border border-white/20 focus:border-indigo-400 focus:ring-indigo-400/40 text-white px-4 py-2.5" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-1">Inpre</label>
                        <input name="ip" type="text" pattern="\d{3,8}" required class="w-full rounded-md bg-white/10 border border-white/20 focus:border-indigo-400 focus:ring-indigo-400/40 text-white px-4 py-2.5" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-1">Teléfono</label>
                        <input name="telefono" type="text" pattern="\d{10}" required class="w-full rounded-md bg-white/10 border border-white/20 focus:border-indigo-400 focus:ring-indigo-400/40 text-white px-4 py-2.5" placeholder="0412000000" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-1">Email</label>
                        <input name="email" type="email" required class="w-full rounded-md bg-white/10 border border-white/20 focus:border-indigo-400 focus:ring-indigo-400/40 text-white px-4 py-2.5" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-1">Mensaje</label>
                    <textarea name="mensaje" minlength="8" required rows="5" class="w-full rounded-md bg-white/10 border border-white/20 focus:border-indigo-400 focus:ring-indigo-400/40 text-white px-4 py-3 resize-y"></textarea>
                </div>
                <p class="text-xs text-white/50">No logras contactarnos? Escríbenos a informaticacolegioabogados@gmail.com</p>
                <div class="flex items-center gap-3 pt-2">
                    <button class="flex-1 inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-6 py-2.5 transition focus:outline-none focus:ring focus:ring-emerald-400/50">Enviar</button>
                    <a href="index.php" class="inline-flex justify-center rounded-md bg-amber-600 hover:bg-amber-500 text-white font-medium px-6 py-2.5 transition focus:outline-none focus:ring focus:ring-amber-400/50">Salir</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php render_footer(); ?>