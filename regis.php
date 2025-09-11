<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrarse</title>
    <link rel="shortcut icon" href="favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>.text-light{color:#f1f5f9}.sticky-bottom{position:fixed;bottom:1rem;right:1rem}</style>

</head>
<body>
        <div class="min-h-screen bg-cover bg-center flex items-center justify-center px-4" style="background-image:url('salonestudiosjuridicos.jpg')">
            <div class="w-full max-w-2xl bg-slate-900/70 backdrop-blur rounded-xl p-8 shadow-lg">
                <h1 class="text-2xl font-bold text-white mb-6">Crea tu cuenta</h1>
                <form action="regis2.php" method="post" class="space-y-6">
                    <?php require_once __DIR__ . '/src/bootstrap.php'; echo csrf_input(); ?>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-200 mb-1">Email</label>
                            <input name="correo" type="email" required placeholder="nombre@ejemplo.com" class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 text-slate-100 px-3 py-2 text-sm outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200 mb-1">Confirmar Email</label>
                            <input name="correo2" type="email" required placeholder="repite tu email" class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 text-slate-100 px-3 py-2 text-sm outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200 mb-1">Contraseña</label>
                            <input name="contra" type="password" pattern=".{8,}" required placeholder="mínimo 8 caracteres" class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 text-slate-100 px-3 py-2 text-sm outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200 mb-1">Confirmar Contraseña</label>
                            <input name="contra2" type="password" pattern=".{8,}" required placeholder="repite contraseña" class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 text-slate-100 px-3 py-2 text-sm outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200 mb-1">Cédula</label>
                            <input name="ci" type="number" max="99999999" required class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 text-slate-100 px-3 py-2 text-sm outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-200 mb-1">Inpre</label>
                            <input name="ip" type="number" max="999999" required class="w-full rounded-md bg-slate-800/70 border border-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 text-slate-100 px-3 py-2 text-sm outline-none" />
                        </div>
                    </div>
                    <p class="text-xs text-slate-300">Evita usar puntos "." o guiones "-" para separar los números</p>
                    <div class="flex gap-4 flex-col md:flex-row">
                        <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-sm rounded-md px-4 py-2 transition">Registrarse</button>
                        <a href="ingre.php" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-md px-4 py-2 transition">Ingresar</a>
                    </div>
                </form>
                <form action="index.php" class="mt-6">
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-white font-medium text-sm rounded-md px-4 py-2 transition">Salir</button>
                </form>
            </div>
            <div class="sticky-bottom">
                <a href="soport.php" class="block"><img src="contact2.png" alt="Soporte" width="100" height="100" class="hover:scale-105 transition" /></a>
            </div>
        </div>
</body>
</html>