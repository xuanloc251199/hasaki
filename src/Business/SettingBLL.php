<?php
require_once __DIR__ . '/../DataAccess/SettingDAL.php';

class SettingBLL {
    private SettingDAL $dal;
    private static ?array $cache = null;

    public function __construct() { $this->dal = new SettingDAL(); }

    /** Loads all settings into in-memory cache (1 query/request). */
    public function loadCache(): void {
        if (self::$cache === null) {
            self::$cache = $this->dal->getAllAsMap();
        }
    }

    public function get(string $key, ?string $default = null): ?string {
        $this->loadCache();
        $val = self::$cache[$key] ?? null;
        return ($val !== null && $val !== '') ? $val : $default;
    }

    public function getAllGrouped(): array { return $this->dal->getAllGrouped(); }

    public function setMany(array $kvs): bool {
        $ok = $this->dal->setMany($kvs);
        self::$cache = null; // invalidate
        return $ok;
    }

    public function set(string $key, ?string $value): bool {
        $ok = $this->dal->set($key, $value);
        self::$cache = null;
        return $ok;
    }
}
