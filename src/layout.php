<?php
require_once __DIR__ . '/bootstrap.php';

// Regenerate session ID once after login to mitigate fixation
function ensure_session_regenerated(): void {
    if (!empty($_SESSION['nameuser']) && empty($_SESSION['_sid_regenerated'])) {
        session_regenerate_id(true);
        $_SESSION['_sid_regenerated'] = 1;
    }
}

function render_header(string $title, string $bgImage): void {
    ?>
    <?php
    // Basic security headers (sent early). They may be ignored if already sent.
    @header('X-Frame-Options: SAMEORIGIN');
    @header('X-Content-Type-Options: nosniff');
    @header('Referrer-Policy: same-origin');
    @header('X-XSS-Protection: 1; mode=block');
    @header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
    // Minimal CSP allowing own origin + bootstrap CDN for CSS; adjust if adding inline scripts.
    $csp = "default-src 'self'; style-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; img-src 'self' data:; script-src 'self' https://cdn.jsdelivr.net; object-src 'none'; frame-ancestors 'self'; base-uri 'self'";
    @header('Content-Security-Policy: ' . $csp);
    ensure_session_regenerated();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= h($title) ?></title>
        <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    </head>
    <body>
    <div style="background: url('<?= h($bgImage) ?>') no-repeat center center fixed;background-size:cover;">
    <?php
}

function render_footer(): void {
    ?>
    <div class="sticky-bottom">
        <a class="img-fluid" href="soport.php">
            <img src="contact2.png" alt="Soporte" width="100" height="100">
        </a>
    </div>
    </div>
    </body>
    </html>
    <?php
}
