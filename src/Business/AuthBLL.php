<?php
require_once __DIR__ . '/../DataAccess/AccountDAL.php';
require_once __DIR__ . '/../DataAccess/CustomerDAL.php';
require_once __DIR__ . '/../DataAccess/EmployeeDAL.php';

class AuthBLL {
    private AccountDAL $accountDAL;
    private CustomerDAL $customerDAL;
    private EmployeeDAL $employeeDAL;

    public function __construct() {
        $this->accountDAL  = new AccountDAL();
        $this->customerDAL = new CustomerDAL();
        $this->employeeDAL = new EmployeeDAL();
    }

    public function login(string $username, string $password): ?array {
        $account = $this->accountDAL->findByUsername($username);
        if (!$account) {
            return null;
        }
        if ((int)$account['TrangThai'] === 0) {
            return ['error' => 'Tài khoản đã bị khóa'];
        }
        if (!password_verify($password, $account['MatKhau'])) {
            return null;
        }

        $_SESSION['user_id']  = (int)$account['MaTaiKhoan'];
        $_SESSION['username'] = $account['TenTaiKhoan'];
        $_SESSION['role']     = (int)$account['LoaiTaiKhoan'];
        $_SESSION['role_name'] = $account['TenLoaiTaiKhoan'];

        // attach profile
        if ((int)$account['LoaiTaiKhoan'] === 3) {
            $customer = $this->customerDAL->getByAccount((int)$account['MaTaiKhoan']);
            if ($customer) {
                $_SESSION['profile_id'] = (int)$customer['MaKhachHang'];
                $_SESSION['display_name'] = $customer['TenKhachHang'];
            }
        } else {
            $stmt = Database::getConnection()->prepare("SELECT * FROM NhanVien WHERE MaTaiKhoan = ?");
            $stmt->execute([$account['MaTaiKhoan']]);
            $employee = $stmt->fetch();
            if ($employee) {
                $_SESSION['profile_id'] = (int)$employee['MaNV'];
                $_SESSION['display_name'] = $employee['TenNV'];
            } else {
                $_SESSION['display_name'] = $account['TenTaiKhoan'];
            }
        }
        return $account;
    }

    public function register(array $data): array {
        $username = trim($data['TenTaiKhoan']);
        $password = $data['MatKhau'];

        if (mb_strlen($username) < 4) {
            return ['error' => 'Tên đăng nhập phải có ít nhất 4 ký tự'];
        }
        if (mb_strlen($password) < 6) {
            return ['error' => 'Mật khẩu phải có ít nhất 6 ký tự'];
        }
        if ($this->accountDAL->findByUsername($username)) {
            return ['error' => 'Tên đăng nhập đã tồn tại'];
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $accountId = $this->accountDAL->insert($username, $hash, 3);

        $customerId = $this->customerDAL->insert([
            'TenKhachHang' => $data['TenKhachHang'] ?? $username,
            'DiaChi'       => $data['DiaChi'] ?? null,
            'SDT'          => $data['SDT'] ?? null,
            'Email'        => $data['Email'] ?? null,
            'GioiTinh'     => $data['GioiTinh'] ?? null,
            'MaTaiKhoan'   => $accountId,
        ]);

        return ['success' => true, 'account_id' => $accountId, 'customer_id' => $customerId];
    }

    public function changePassword(int $accountId, string $oldPassword, string $newPassword): array {
        $account = $this->accountDAL->getById($accountId);
        if (!$account) {
            return ['error' => 'Tài khoản không tồn tại'];
        }
        if (!password_verify($oldPassword, $account['MatKhau'])) {
            return ['error' => 'Mật khẩu cũ không đúng'];
        }
        if (mb_strlen($newPassword) < 6) {
            return ['error' => 'Mật khẩu mới phải có ít nhất 6 ký tự'];
        }
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->accountDAL->updatePassword($accountId, $hash);
        return ['success' => true];
    }

    public function logout(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
