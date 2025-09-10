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
    <nav class="navbar navbar-expand-lg fixed-top bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="logo" width="30" height="30">
            </a>
            <div class="btn-group me-2">
                <a href="search.php" class="btn btn-outline-success active">Buscar solvencia</a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBar" aria-controls="navBar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navBar">
                <ul class="navbar-nav ms-auto">
                    <?php if (!$logged): ?>
                        <li class="nav-item"><a class="nav-link" href="ingre.php">Iniciar sesión</a></li>
                        <li class="nav-item"><a class="nav-link" href="regis.php">Registrarse</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="ingre.php">Ingresar</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
                    <?php endif; ?>
                </ul>
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
    <nav class="navbar fixed-top" style="background-color: rgba(255,255,255,0);">
        <div class="container-fluid">
            <a class="navbar-brand"><small class='text-light fs-6'><?= h($display) ?></small></a>
            <button class="navbar-toggler" type="button" style="background-color:#FFFFFF;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel"><?= h($title) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item"><a class="nav-link" href="pageuser.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="recpass.php">Cambiar contraseña</a></li>
                        <li class="nav-item"><a class="nav-link" href="recemail.php">Cambiar email</a></li>
                        <li class="nav-item"><a class="nav-link" href="delete.php">Eliminar usuario</a></li>
                        <li class="nav-item"><a class="nav-link" href="datedb.php">Datos de registro</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <?php
}
