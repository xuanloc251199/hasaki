# HASAKI - Website bán mỹ phẩm

Đồ án tốt nghiệp **"Thiết kế Website bán mỹ phẩm Hasaki"** - sinh viên **Lê Thị Kim Phụng (D22CTC1)** - Trường Đại học Xây dựng Miền Trung.

Source code được xây dựng lại bám sát theo báo cáo đồ án:
- **Frontend**: HTML5 + SCSS (biên dịch ra CSS) + Vanilla JavaScript
- **Backend**: PHP thuần (PHP 8+) theo mô hình **3-Layer (DataAccess → Business → Controller/View)**
- **Database**: MySQL 8

---

## 📁 Cấu trúc thư mục

```
hasaki/
├── config/                 # Config + DB connection (Database::getConnection())
│   ├── config.php
│   └── database.php
├── database/
│   └── schema.sql          # Schema + seed data (11 bảng)
├── src/
│   ├── DataAccess/         # Tầng truy xuất dữ liệu (PDO)
│   │   ├── BaseDAL.php
│   │   ├── ProductDAL.php, CategoryDAL.php
│   │   ├── CustomerDAL.php, EmployeeDAL.php
│   │   ├── AccountDAL.php, InvoiceDAL.php
│   │   ├── ImportInvoiceDAL.php, WarehouseDAL.php
│   ├── Business/           # Tầng xử lý nghiệp vụ
│   │   ├── AuthBLL.php, CartBLL.php
│   │   ├── ProductBLL.php, CategoryBLL.php
│   │   ├── CustomerBLL.php, EmployeeBLL.php
│   │   ├── AccountBLL.php, InvoiceBLL.php
│   └── helpers/
│       └── functions.php   # Hàm tiện ích chung
├── public/                 # Document root (Controller / View)
│   ├── index.php           # Trang chủ
│   ├── products.php        # Danh sách sản phẩm
│   ├── product-detail.php  # Chi tiết sản phẩm
│   ├── cart.php            # Giỏ hàng
│   ├── cart-action.php     # Add/update/remove cart
│   ├── checkout.php        # Thanh toán
│   ├── order-success.php
│   ├── login.php / register.php / logout.php
│   ├── account.php         # Trang tài khoản KH
│   ├── about.php
│   ├── admin/              # Phân hệ quản trị
│   │   ├── login.php
│   │   ├── index.php       # Dashboard tổng quan
│   │   ├── products.php, categories.php
│   │   ├── customers.php, employees.php, accounts.php
│   │   ├── invoices.php, import-invoices.php
│   │   ├── warehouse.php, stats.php
│   │   └── includes/layout-top.php, layout-bottom.php
│   ├── includes/header.php, footer.php, product-card.php
│   └── assets/
│       ├── scss/           # SCSS sources
│       │   ├── style.scss (entry)
│       │   ├── _variables.scss, _mixins.scss, _base.scss
│       │   ├── _header.scss, _footer.scss
│       │   ├── _home.scss, _product.scss, _cart.scss, _auth.scss
│       │   └── admin/admin.scss, _admin-base.scss
│       ├── css/            # CSS đã biên dịch (style.css, admin.css)
│       ├── js/main.js
│       └── images/         # Ảnh sản phẩm
├── index.php / .htaccess   # Forward đến /public
├── package.json            # Script biên dịch SCSS
└── README.md
```

---

## 🚀 Cài đặt

### 1. Yêu cầu môi trường
- **Laragon** / **XAMPP** / **WAMP** với:
  - PHP 8.0+
  - MySQL 8 hoặc MariaDB 10.5+
  - Apache (hoặc dùng PHP built-in server để test nhanh)
- Node.js (nếu muốn biên dịch lại SCSS)

### 2. Đặt source code
Đặt thư mục dự án vào `www/` của Laragon:
```
I:\laragon\www\hasaki\
```

Hasaki sẽ truy cập tại: **http://localhost/hasaki** (hoặc cấu hình Auto Virtual Host của Laragon: `http://hasaki.test`).

### 3. Tạo database
Mở MySQL và import schema:
```bash
mysql -uroot < database/schema.sql
```

(hoặc dùng phpMyAdmin / HeidiSQL import file `database/schema.sql`)

Schema sẽ tự tạo database `hasaki_db` với:
- 11 bảng theo đúng thiết kế trong báo cáo
- 6 danh mục, 16 sản phẩm mẫu
- 3 tài khoản mẫu (xem ở dưới)

### 4. Chỉnh thông tin kết nối DB
Mở `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'hasaki_db');
define('DB_USER', 'root');
define('DB_PASS', '');     // mặc định Laragon root không mật khẩu
```

### 5. (Tùy chọn) Biên dịch lại SCSS
```bash
npm install
npm run build:css        # build 1 lần
npm run watch:css        # watch khi develop
```

CSS đã được biên dịch sẵn trong `public/assets/css/` nên có thể bỏ qua bước này nếu chưa chỉnh SCSS.

---

## 🔑 Tài khoản mẫu

Tất cả tài khoản dùng chung mật khẩu **`123456`** (đã hash bcrypt trong seed):

| Tên đăng nhập | Vai trò    | Truy cập                                    |
|---------------|------------|---------------------------------------------|
| `admin`       | Admin      | `/admin/login.php` (toàn quyền quản trị)    |
| `nhanvien1`   | Nhân viên  | `/admin/login.php` (truy cập trang quản trị)|
| `khach01`     | Khách hàng | `/login.php` (mua hàng)                     |

---

## 📋 Chức năng đã triển khai

### Phân hệ người dùng (Khách hàng)
- ✅ Trang chủ - Hero banner, danh mục, sản phẩm khuyến mãi/mới ra
- ✅ Trang sản phẩm - lọc theo danh mục, tìm kiếm
- ✅ Trang chi tiết sản phẩm - thumbnail, options, mua ngay
- ✅ Giỏ hàng - thêm/sửa số lượng/xóa, lưu session
- ✅ Thanh toán (checkout) - thông tin giao hàng + COD/VNPay
- ✅ Đăng ký / Đăng nhập / Đăng xuất / Đổi mật khẩu
- ✅ Trang tài khoản - sửa thông tin + xem lịch sử đơn hàng
- ✅ Trang giới thiệu

### Phân hệ quản trị (Admin / Nhân viên)
- ✅ Trang đăng nhập riêng cho admin
- ✅ Dashboard tổng quan (KPI: doanh thu, đơn, sản phẩm, khách hàng)
- ✅ Quản lý sản phẩm (CRUD + tìm kiếm)
- ✅ Quản lý danh mục (CRUD + tìm kiếm)
- ✅ Quản lý khách hàng (CRUD + tìm kiếm)
- ✅ Quản lý nhân viên (CRUD + tìm kiếm)
- ✅ Quản lý tài khoản (Thêm / khóa / mở khóa / xóa)
- ✅ Quản lý đơn hàng bán (xem chi tiết, cập nhật trạng thái, xóa)
- ✅ Quản lý đơn hàng nhập (xem, xóa)
- ✅ Quản lý kho hàng (sửa số lượng tồn)
- ✅ Thống kê doanh thu theo ngày

---

## 🗄️ Mô hình 3-Layer

Theo đúng mục **2.6 Mô hình thao tác dữ liệu 3 layer** trong báo cáo:

```
[ Controller (View - public/*.php, public/admin/*.php) ]
                        ↓
[      Business Layer (src/Business/*BLL.php)           ]
                        ↓
[      DataAccess Layer (src/DataAccess/*DAL.php)        ]
                        ↓
[              MySQL (PDO)                              ]
```

- **DataAccess (DAL)**: chỉ thực hiện truy vấn SQL (PDO prepared statements). Mỗi entity có 1 file DAL.
- **Business (BLL)**: validate input, xử lý nghiệp vụ (login, checkout, decrease stock, status update...), gọi DAL.
- **Controller / View**: file PHP trong `public/` nhận request, gọi BLL, render view.

---

## 🔒 Bảo mật

- Mật khẩu lưu trữ bằng **bcrypt** (`password_hash` / `password_verify`).
- Tất cả query qua **PDO Prepared Statement** → chống SQL injection.
- Tất cả output qua hàm `e()` (`htmlspecialchars`) → chống XSS.
- Session cho cart và authentication.
- `require_login()` / `require_admin()` bảo vệ các trang nội bộ.

---

## 🧪 Kiểm thử nhanh

Khởi động PHP built-in server:
```bash
cd public
php -S localhost:8080
```

Sau đó vào:
- http://localhost:8080 - Trang chủ
- http://localhost:8080/products.php
- http://localhost:8080/login.php  (đăng nhập `khach01` / `123456`)
- http://localhost:8080/admin/login.php  (đăng nhập `admin` / `123456`)