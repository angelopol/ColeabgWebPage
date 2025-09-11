<?php
require_once __DIR__ . '/bootstrap.php';

function render_nav(): void {
    $logged = !empty($_SESSION);
    // Simple inactivity timeout check retained from original index.php
    if ($logged && isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1600)) {
        session_unset();
        session_destroy();
        $logged = false;
    }
    $_SESSION['LAST_ACTIVITY'] = time();
    ?>
        <nav class="fixed top-0 inset-x-0 z-40 backdrop-blur bg-white/70 border-b border-slate-200/70">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex h-14 items-center justify-between">
                <a href="index.php" class="flex items-center gap-2 font-semibold text-slate-700">
                    <img src="logo.png" alt="logo" class="h-8 w-8" />
                    <span class="hidden sm:inline">Colegio</span>
                </a>
                <div class="flex items-center gap-6">
                    <a href="search.php" class="text-sm font-medium text-emerald-700 hover:text-emerald-600">Buscar solvencia</a>
                    <div class="relative" x-data="{open:false}">
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="inline-flex items-center gap-1 text-sm font-medium text-slate-600 hover:text-slate-800 focus:outline-none">
                            <span>Menú</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div class="hidden absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-slate-900/10 py-1 text-sm">
                            <?php if (!$logged): ?>
                                <a href="ingre.php" class="block px-3 py-2 hover:bg-slate-50">Iniciar sesión</a>
                                <a href="regis.php" class="block px-3 py-2 hover:bg-slate-50">Registrarse</a>
                            <?php else: ?>
                                <a href="ingre.php" class="block px-3 py-2 hover:bg-slate-50">Ingresar</a>
                                <a href="logout.php" class="block px-3 py-2 hover:bg-slate-50">Cerrar sesión</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    <?php
}

function render_auth_nav(string $title = 'Menu'): void {
    require_auth();
    $display = 'Usuario';
    // Attempt to get descriptive name if repositories available
    try {
        if (class_exists('UserRepository')) {
            $userRepo = new UserRepository(db());
            $user = $userRepo->findByEmail($_SESSION['nameuser']);
            if ($user && class_exists('LawyerRepository')) {
                $lawRepo = new LawyerRepository(db());
                $lawyer = $lawRepo->findByCedula($user['CodClie'] ?? '') ?? $lawRepo->findById3($user['CodClie'] ?? '');
                if ($lawyer && !empty($lawyer['Descrip'])) { $display = $lawyer['Descrip']; }
            }
        }
    } catch (Throwable $e) { /* ignore */ }
    ?>
        <nav class="fixed top-0 inset-x-0 z-40 bg-gradient-to-b from-slate-900/60 to-transparent">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex h-14 items-center justify-between">
                <span class="text-slate-100 text-sm font-semibold truncate max-w-xs"><?= h($display) ?></span>
                <div class="relative">
                    <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="inline-flex items-center gap-1 text-xs font-medium bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded">
                        <span><?= h($title) ?></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-slate-900/95 backdrop-blur ring-1 ring-slate-700 py-2 text-sm text-slate-100">
                        <a href="pageuser.php" class="block px-4 py-2 hover:bg-slate-700/60">Inicio</a>
                        <a href="recpass.php" class="block px-4 py-2 hover:bg-slate-700/60">Cambiar contraseña</a>
                        <a href="recemail.php" class="block px-4 py-2 hover:bg-slate-700/60">Cambiar email</a>
                        <a href="delete.php" class="block px-4 py-2 hover:bg-slate-700/60">Eliminar usuario</a>
                        <a href="datedb.php" class="block px-4 py-2 hover:bg-slate-700/60">Datos de registro</a>
                        <a href="logout.php" class="block px-4 py-2 hover:bg-slate-700/60">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </nav>
    <?php
}
