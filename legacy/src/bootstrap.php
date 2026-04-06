<?php
// Central bootstrap: session start, autoload (if later added), and shared helpers.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple .env loader (KEY=VALUE) ignoring commented and empty lines.
(function(){
    $envPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
    if (is_readable($envPath)) {
        foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                [$k,$v] = $parts;
                $k = trim($k);
                $v = trim($v);
                if ($k !== '' && getenv($k) === false) {
                    putenv("$k=$v");
                    $_ENV[$k] = $v;
                }
            }
        }
    }
})();

require_once __DIR__ . '/../conn.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = DataConnect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    return $pdo;
}

function sanitize_int($value, $default = null) {
    if ($value === null || $value === '') return $default;
    return filter_var($value, FILTER_VALIDATE_INT, ["options" => ["default" => $default]]);
}

function redirect($location) {
    header("Location: $location");
    exit;
}

function h($str) { return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); }

function valid_year_or_null($yearRaw): ?int {
    if ($yearRaw === null || $yearRaw === '') return null;
    if (!ctype_digit($yearRaw)) return null;
    $y = (int)$yearRaw;
    $current = (int)date('Y');
    if ($y < 1990 || $y > $current + 1) return null; // allow slight future overlap
    return $y;
}

function alert_box(string $type, string $message): string {
    return "<div class='alert alert-" . h($type) . "' role='alert'>" . h($message) . "</div>";
}

function back_links_block(): string {
    return "<form action='regis.php' class='mt-2'><button type='submit' class='btn btn-primary'>Intente de nuevo</button></form><br><form action='index.php'><button type='submit' class='btn btn-warning'>Salir</button></form>";
}

function require_auth(): void {
    if (empty($_SESSION['nameuser'])) { redirect('index.php'); }
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1600)) {
        session_unset();
        session_destroy();
        redirect('index.php');
    }
    $_SESSION['LAST_ACTIVITY'] = time();
}

function send_email(string $to, string $subject, string $html, string $alt = ''): bool {
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) return false;
    $host = getenv('SMTP_HOST');
    $user = getenv('SMTP_USER');
    $pass = getenv('SMTP_PASS');
    if (!$host || !$user) return false; // SMTP not configured
    try {
        if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            $base = dirname(__DIR__);
            @require_once $base . '/PHPMailer.php';
            @require_once $base . '/Exception.php';
            @require_once $base . '/SMTP.php';
        }
        if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) return false;
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $pass ?: '';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->setFrom($user, 'Colegio de Abogados del Estado Carabobo');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body = $html;
        $mail->AltBody = $alt ?: strip_tags($html);
        return $mail->send();
    } catch (Throwable $e) {
        error_log('Email send failed: ' . $e->getMessage());
        return false;
    }
}

// CSRF protection helpers
function csrf_token(): string {
    if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time']) || (time() - $_SESSION['csrf_token_time']) > 1800) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

function csrf_input(): string {
    return "<input type='hidden' name='csrf_token' value='" . h(csrf_token()) . "'>";
}

function verify_csrf(): bool {
    $token = $_POST['csrf_token'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

// Simple security event logger
function log_security_event(string $event, array $context = []): void {
    $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs';
    if (!is_dir($dir)) {@mkdir($dir, 0775, true);}    
    $line = date('c') . ' ' . $event;
    if ($context) { $line .= ' ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); }
    $line .= PHP_EOL;
    @file_put_contents($dir . DIRECTORY_SEPARATOR . 'security.log', $line, FILE_APPEND | LOCK_EX);
}
