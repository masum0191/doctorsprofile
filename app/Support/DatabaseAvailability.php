<?php

namespace App\Support;

class DatabaseAvailability
{
    public static function check(): bool
    {
        static $available = null;

        if ($available !== null) {
            return $available;
        }

        $connection = config('database.default');
        $config = config("database.connections.{$connection}", []);
        $driver = $config['driver'] ?? null;

        if ($driver === 'sqlite') {
            $database = $config['database'] ?? null;

            return $available = $database === ':memory:' || ($database && file_exists($database));
        }

        if (! in_array($driver, ['mysql', 'mariadb', 'pgsql', 'sqlsrv'], true)) {
            return $available = true;
        }

        $host = $config['host'] ?? '127.0.0.1';

        if (str_starts_with($host, '/')) {
            return $available = true;
        }

        $port = (int) ($config['port'] ?? ($driver === 'pgsql' ? 5432 : 3306));
        $socket = @fsockopen($host, $port, $errno, $errstr, 0.25);

        if (is_resource($socket)) {
            fclose($socket);

            return $available = true;
        }

        return $available = false;
    }
}
