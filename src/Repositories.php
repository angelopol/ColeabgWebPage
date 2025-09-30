<?php
// Data access layer wrappers to reduce duplication.

class LawyerRepository {
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    // Using positional placeholders (?) for compatibility with some ODBC SQL Server drivers.
    private function fetchOne(string $sql, array $params, string $tag): ?array {
        try {
            $st = $this->db->prepare($sql);
            // Bind positionally to show alternative injection method.
            foreach (array_values($params) as $idx => $val) {
                $st->bindValue($idx + 1, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $st->execute();
            $r = $st->fetch();
            return $r ?: null;
        } catch (Throwable $e) {
            error_log("SQL_ERROR {$tag}: " . $e->getMessage() . " | SQL=" . $sql . " | PARAMS=" . json_encode($params));
            return null;
        }
    }

    public function findByCedulaOrId3Like(string $needle): array {
        // Extended to also match Clase for broader disambiguation.
        $sql = "SELECT * FROM SACLIE WHERE CodClie LIKE ? OR ID3 LIKE ? OR Clase LIKE ?";
        $like = "%$needle%";
        $st = $this->db->prepare($sql);
        $st->bindValue(1, $like, PDO::PARAM_STR);
        $st->bindValue(2, $like, PDO::PARAM_STR);
        $st->bindValue(3, $like, PDO::PARAM_STR);
        $st->execute();
        return $st->fetchAll();
    }

    public function findByCedula(string $cedula): ?array {
    // Use LIKE with automatic wildcards
    $pattern = "%$cedula%";
    return $this->fetchOne("SELECT TOP 1 * FROM SACLIE WHERE CodClie LIKE ?", [$pattern], 'findByCedula');
    }

    public function findById3(string $id3): ?array {
    // Use LIKE with automatic wildcards
    $pattern = "%$id3%";
    return $this->fetchOne("SELECT TOP 1 * FROM SACLIE WHERE ID3 LIKE ?", [$pattern], 'findById3');
    }

    public function getInscriptionData(string $codClie): ?array {
        $pattern = "%$codClie%";
        return $this->fetchOne("SELECT TOP 1 * FROM SACLIE_08 WHERE CodClie LIKE ?", [$pattern], 'getInscriptionData');
    }

    public function getSolvency(string $codClie): ?array {
        $pattern = "%$codClie%";
        return $this->fetchOne("SELECT TOP 1 * FROM SOLV WHERE CodClie LIKE ? AND Status = 1 ORDER BY hasta DESC", [$pattern], 'getSolvency');
    }

    public function findByInpre(string $inpre): ?array {
    // Try exact match first (preferred when user is searching by Inpre), then fall back to LIKE
    $exact = $this->fetchOne("SELECT TOP 1 * FROM SACLIE WHERE Clase = ?", [$inpre], 'findByInpre_exact');
    if ($exact) return $exact;
    $pattern = "%$inpre%";
    return $this->fetchOne("SELECT TOP 1 * FROM SACLIE WHERE Clase LIKE ?", [$pattern], 'findByInpre_like');
    }

    public function findByClaseLike(string $needle): array {
        $sql = "SELECT * FROM SACLIE WHERE Clase LIKE ?";
        $like = "%$needle%";
        $st = $this->db->prepare($sql);
        $st->bindValue(1, $like, PDO::PARAM_STR);
        $st->execute();
        return $st->fetchAll();
    }
}

class OperationsRepository {
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function operationsByClient(string $cedulaOrId3, ?int $year = null): array {
        $like = "%$cedulaOrId3%";
        if ($year !== null) {
            // Order newest first
            $sql = 'SELECT * FROM SAFACT WHERE (CodClie LIKE ? OR ID3 LIKE ?) AND FechaE LIKE ? ORDER BY FechaE DESC';
            $params = [$like, $like, "%$year%"];
        } else {
            // Recent operations: newest first (descending date)
            $sql = 'SELECT * FROM SAFACT WHERE CodClie LIKE ? OR ID3 LIKE ? ORDER BY FechaE DESC';
            $params = [$like, $like];
        }
        $st = $this->db->prepare($sql);
        foreach ($params as $i => $v) { $st->bindValue($i+1, $v, PDO::PARAM_STR); }
        $st->execute();
        return $st->fetchAll();
    }

    public function anyOperationHistoric(string $cedulaOrId3): bool {
        $like = "%$cedulaOrId3%";
    $st = $this->db->prepare('SELECT TOP 1 1 FROM SAFACT WHERE CodClie LIKE ? OR ID3 LIKE ?');
    $st->bindValue(1, $like, PDO::PARAM_STR);
    $st->bindValue(2, $like, PDO::PARAM_STR);
        $st->execute();
        return (bool)$st->fetchColumn();
    }

    public function allOperations(string $cedulaOrId3): array {
        $like = "%$cedulaOrId3%";
    $st = $this->db->prepare('SELECT * FROM SAFACT WHERE CodClie LIKE ? OR ID3 LIKE ? ORDER BY FechaE DESC');
    $st->bindValue(1, $like, PDO::PARAM_STR);
    $st->bindValue(2, $like, PDO::PARAM_STR);
        $st->execute();
        return $st->fetchAll();
    }

    public function allOperationsSAACXCByClient(string $codClie): array {
        $like = "%$codClie%";
        $st = $this->db->prepare('SELECT * FROM SAACXC WHERE CodClie LIKE ? ORDER BY FechaE DESC');
        $st->bindValue(1, $like, PDO::PARAM_STR);
        $st->execute();
        return $st->fetchAll();
    }

    public function operationsByInpre(string $codClie, ?int $year = null): array {
        $like = "%$codClie%";
        if ($year !== null) {
            $sql = 'SELECT * FROM SAFACT WHERE (CodClie LIKE ? OR ID3 LIKE ?) AND FechaE LIKE ? ORDER BY FechaE DESC';
            $params = [$like, $like, "%$year%"];
        } else {
            $sql = 'SELECT * FROM SAFACT WHERE CodClie LIKE ? OR ID3 LIKE ? ORDER BY FechaE DESC';
            $params = [$like, $like];
        }
        $st = $this->db->prepare($sql);
        foreach ($params as $i => $v) { $st->bindValue($i+1, $v, PDO::PARAM_STR); }
        $st->execute();
        return $st->fetchAll();
    }
}

class UserRepository {
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    private function fetchOne(string $sql, array $params, string $tag): ?array {
        try {
            $st = $this->db->prepare($sql);
            foreach (array_values($params) as $i => $v) {
                $st->bindValue($i+1, $v, PDO::PARAM_STR);
            }
            $st->execute();
            $r = $st->fetch();
            return $r ?: null;
        } catch (Throwable $e) {
            error_log("SQL_ERROR {$tag}: " . $e->getMessage() . " | SQL=" . $sql . " | PARAMS=" . json_encode($params));
            return null;
        }
    }

    public function findByEmailAndPassword(string $email, string $pass): ?array {
        return $this->fetchOne('SELECT TOP 1 * FROM USUARIOS WHERE email = ? AND pass = ?', [$email, $pass], 'findByEmailAndPassword');
    }

    public function findByEmail(string $email): ?array {
        return $this->fetchOne('SELECT TOP 1 * FROM USUARIOS WHERE email = ?', [$email], 'findByEmail');
    }

    public function verifyPassword(string $email, string $pass): ?array {
        $user = $this->findByEmail($email);
        if (!$user) return null;
        if (isset($user['pass']) && str_starts_with((string)$user['pass'], '$2y$')) {
            return password_verify($pass, $user['pass']) ? $user : null;
        }
        return $user['pass'] === $pass ? $user : null;
    }

    public function emailExists(string $email): bool {
        $st = $this->db->prepare('SELECT TOP 1 1 FROM USUARIOS WHERE email = ?');
        $st->bindValue(1, $email, PDO::PARAM_STR);
        $st->execute();
        return (bool)$st->fetchColumn();
    }

    public function codClieExists(string $codClie): bool {
        $st = $this->db->prepare('SELECT TOP 1 1 FROM USUARIOS WHERE CodClie LIKE ?');
        $st->bindValue(1, "%$codClie%", PDO::PARAM_STR);
        $st->execute();
        return (bool)$st->fetchColumn();
    }

    public function claseExists(string $clase): bool {
        $st = $this->db->prepare('SELECT TOP 1 1 FROM USUARIOS WHERE Clase LIKE ?');
        $st->bindValue(1, "%$clase%", PDO::PARAM_STR);
        $st->execute();
        return (bool)$st->fetchColumn();
    }

    public function createUser(string $email, string $pass, string $clase, string $codClie): bool {
        $st = $this->db->prepare('INSERT INTO USUARIOS (email, pass, Clase, CodClie) VALUES (?, ?, ?, ?)');
        $st->bindValue(1, $email, PDO::PARAM_STR);
        $st->bindValue(2, $pass, PDO::PARAM_STR);
        $st->bindValue(3, $clase, PDO::PARAM_STR);
        $st->bindValue(4, $codClie, PDO::PARAM_STR);
        return $st->execute();
    }

    public function createUserHashed(string $email, string $plainPass, string $clase, string $codClie): bool {
        $hash = password_hash($plainPass, PASSWORD_BCRYPT);
        $st = $this->db->prepare('INSERT INTO USUARIOS (email, pass, Clase, CodClie) VALUES (?, ?, ?, ?)');
        $st->bindValue(1, $email, PDO::PARAM_STR);
        $st->bindValue(2, $hash, PDO::PARAM_STR);
        $st->bindValue(3, $clase, PDO::PARAM_STR);
        $st->bindValue(4, $codClie, PDO::PARAM_STR);
        return $st->execute();
    }

    public function updatePasswordHashed(string $email, string $newPlain): bool {
        $hash = password_hash($newPlain, PASSWORD_BCRYPT);
        $st = $this->db->prepare('UPDATE USUARIOS SET pass = ? WHERE email = ?');
        $st->bindValue(1, $hash, PDO::PARAM_STR);
        $st->bindValue(2, $email, PDO::PARAM_STR);
        return $st->execute();
    }

    public function updateEmail(string $currentEmail, string $newEmail): bool {
        $st = $this->db->prepare('UPDATE USUARIOS SET email = ? WHERE email = ?');
        $st->bindValue(1, $newEmail, PDO::PARAM_STR);
        $st->bindValue(2, $currentEmail, PDO::PARAM_STR);
        return $st->execute();
    }

    public function deleteByEmail(string $email): bool {
        $st = $this->db->prepare('DELETE FROM USUARIOS WHERE email = ?');
        $st->bindValue(1, $email, PDO::PARAM_STR);
        return $st->execute();
    }
}
