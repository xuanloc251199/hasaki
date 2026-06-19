<?php
require_once __DIR__ . '/../DataAccess/AccountDAL.php';

class AccountBLL {
    private AccountDAL $dal;

    public function __construct() { $this->dal = new AccountDAL(); }

    public function getAll(): array { return $this->dal->getAll(); }
    public function getById(int $id): ?array { return $this->dal->getById($id); }
    public function search(string $kw): array { return $this->dal->search($kw); }

    public function create(string $username, string $password, int $loaiTK): array {
        if (mb_strlen($username) < 4) return ['error' => 'Tên đăng nhập phải >= 4 ký tự'];
        if (mb_strlen($password) < 6) return ['error' => 'Mật khẩu phải >= 6 ký tự'];
        if ($this->dal->findByUsername($username)) return ['error' => 'Tên đăng nhập đã tồn tại'];
        $id = $this->dal->insert($username, password_hash($password, PASSWORD_BCRYPT), $loaiTK);
        return ['success' => true, 'id' => $id];
    }

    public function resetPassword(int $id, string $newPassword): array {
        if (mb_strlen($newPassword) < 6) return ['error' => 'Mật khẩu phải >= 6 ký tự'];
        $this->dal->updatePassword($id, password_hash($newPassword, PASSWORD_BCRYPT));
        return ['success' => true];
    }

    public function lock(int $id): bool { return $this->dal->setStatus($id, 0); }
    public function unlock(int $id): bool { return $this->dal->setStatus($id, 1); }
    public function delete(int $id): bool { return $this->dal->delete($id); }
}
