<?php
/**
 * BaseDAL - parent for all data access classes (3-layer pattern)
 */

require_once __DIR__ . '/../../config/database.php';

abstract class BaseDAL {
    protected PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }
}
