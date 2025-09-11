<?php
require_once __DIR__ . '/src/bootstrap.php';
require_once __DIR__ . '/src/layout.php';

if (!empty($_SESSION['nameuser'])) {
        header('Location: pageuser.php');
        exit;
}

$_SESSION['reccontra'] = 1;
render_header('Recuperar Contraseña', 'piscina.jpg');
?>
<div class="min-h-screen px-4 py-10">
    <div class="max-w-lg mx-auto">
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl shadow p-8">
            <h1 class="text-2xl font-semibold text-white tracking-tight mb-6">Ingresa tus datos de usuario</h1>
            <form action="reccontra2.php" method="post" class="space-y-5">
                <?php echo csrf_input(); ?>
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-1">Email</label>
                    <input name="correo" type="email" required placeholder="nombre@ejemplo.com" class="w-full rounded-md bg-white/10 border border-white/20 focus:border-indigo-400 focus:ring-indigo-400/40 text-white px-4 py-2.5" />
                </div>
                <p class="text-xs text-white/60">¿No recuerdas tu email? Contacta al <a href="soport.php" class="text-emerald-400 hover:text-emerald-300 underline">Soporte</a></p>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-1">Cédula</label>
                        <input name="ci" type="text" pattern="\d{5,11}" required class="w-full rounded-md bg-white/10 border border-white/20 focus:border-indigo-400 focus:ring-indigo-400/40 text-white px-4 py-2.5" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-1">Inpre</label>
                        <input name="ip" type="text" pattern="\d{3,8}" required class="w-full rounded-md bg-white/10 border border-white/20 focus:border-indigo-400 focus:ring-indigo-400/40 text-white px-4 py-2.5" />
                    </div>
                </div>
                <button type="submit" class="w-full inline-flex justify-center rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-6 py-2.5">Recuperar</button>
            </form>
            <form action='index.php' class='mt-6'>
                <button type="submit" class="w-full inline-flex justify-center rounded-md bg-amber-500 hover:bg-amber-400 text-white font-medium px-6 py-2.5">Salir</button>
            </form>
        </div>
        <div class="fixed bottom-4 right-4">
            <a href="soport.php" class="block"><img src="contact2.png" alt="Soporte" width="100" height="100" class="hover:scale-105 transition" /></a>
        </div>
    </div>
</div>
<?php render_footer(); ?>