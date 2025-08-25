<?php
declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

class Db
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../config/app.php';
            try {
                self::$pdo = new PDO(
                    $config['db']['dsn'],
                    $config['db']['username'],
                    $config['db']['password'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                self::$pdo->exec('SET NAMES utf8mb4');
            } catch (PDOException $e) {
                throw new PDOException('DB connection failed: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
