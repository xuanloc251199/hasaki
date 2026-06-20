# Session Log

Lịch sử các phiên làm việc với dự án Hasaki.

---

## [2026-06-20] — Phân trang, sửa lỗi thanh toán & cho phép đặt hàng khách vãng lai

### Mục tiêu
Thêm phân trang cho trang sản phẩm và các trang quản trị; sửa lỗi crash khi thanh toán bằng tài khoản admin; cho phép đặt hàng không cần đăng nhập; sửa lỗi giao diện ở phần chọn hình thức thanh toán.

### Changelog chi tiết theo task

#### Task 1: Thêm phân trang (sản phẩm + admin)
- **Mô tả:** Trang sản phẩm phía khách và toàn bộ bảng danh sách trong admin trước đây hiển thị tất cả bản ghi trên một trang. Thêm phân trang dùng helper tái sử dụng (cắt mảng trong bộ nhớ — phù hợp quy mô đồ án, hoạt động đồng nhất cho cả tìm kiếm/lọc).
- **Thay đổi:**
  - `src/helpers/functions.php` — Thêm 3 hàm: `current_page()` (đọc `?page=`), `paginate()` (cắt mảng + clamp số trang, trả về items/total/total_pages…), `render_pagination()` (thanh phân trang dùng **inline style** để không phụ thuộc bản build Tailwind/SCSS đã purge; giữ lại `?q=`/`?cat=`, có prev/next + dấu `…`).
  - `public/products.php` — Phân trang lưới sản phẩm **12/trang** (vừa với layout 2/3/4 cột). Số "Tìm thấy N sản phẩm" vẫn hiển thị tổng thật; điều kiện rỗng đổi sang dùng `$totalProducts`.
  - `public/admin/products.php`, `categories.php`, `customers.php`, `employees.php`, `accounts.php`, `invoices.php`, `import-invoices.php`, `warehouse.php` — Phân trang **10/trang**. Riêng `warehouse.php` và `import-invoices.php` đổi "Tổng: count($rows)" sang `$pg['total']` (vì `$rows` giờ chỉ là 1 trang).
- **Ghi chú:** `admin/menus.php` là layout thẻ gom nhóm theo vị trí (dữ liệu nhỏ) nên không phân trang. Hai con số 10/12 là hằng số, đổi 1 dòng nếu muốn đồng nhất.

#### Task 2: Sửa lỗi thanh toán bằng tài khoản admin
- **Mô tả:** Đặt hàng bằng tài khoản admin gây `Warning: array offset on null` (checkout.php:31) và `PDOException` vi phạm khóa ngoại `hoadonban_ibfk_1`. Nguyên nhân: tài khoản admin/nhân viên không có bản ghi `khachhang` → `getByAccount()` trả `null` → `MaKhachHang` = 0, mà `hoadonban.MaKhachHang` là `INT NOT NULL` + FK.
- **Thay đổi:**
  - `public/checkout.php` — Phát hiện tài khoản đã đăng nhập nhưng không có hồ sơ khách hàng (admin/nhân viên) và **chặn có thông báo** ("Tài khoản quản trị/nhân viên không thể đặt hàng…"), chuyển hướng về giỏ hàng thay vì crash.
- **Ghi chú:** Lựa chọn "chặn & báo lỗi" là theo quyết định của người dùng (thay vì tự tạo hồ sơ KH cho admin).

#### Task 3: Cho phép đặt hàng không cần đăng nhập (guest checkout)
- **Mô tả:** Cho khách chưa đăng nhập đặt hàng. Vì `hoadonban.MaKhachHang` `NOT NULL` + FK và `hoadonban` không lưu tên khách, mỗi đơn của khách vãng lai sẽ tự tạo một bản ghi `khachhang` (`MaTaiKhoan = NULL`) từ dữ liệu form → giữ nguyên ràng buộc DB và admin vẫn thấy đủ tên/SĐT/địa chỉ.
- **Thay đổi:**
  - `public/checkout.php` — Bỏ `require_login()`; thêm cờ `$isGuest`; khách vãng lai được validate (tên/SĐT/địa chỉ) rồi tạo hồ sơ KH; thành viên dùng hồ sơ sẵn có; admin/nhân viên vẫn bị chặn. Lưu mã đơn vào `$_SESSION['guest_orders']`. Thêm banner nhắc đăng nhập + ô "Họ và tên" cho khách.
  - `public/order-success.php` — Bỏ `require_login()`; khách chỉ xem được đơn vừa đặt trong phiên của mình (`$_SESSION['guest_orders']`) để chống xem trộm đơn người khác qua `?id=`; ẩn link "Xem đơn hàng" (cần login) với khách.
- **Ghi chú:** Mỗi đơn khách vãng lai tạo 1 bản ghi `khachhang` mới (không gộp theo SĐT) — chấp nhận được cho đồ án. Đã kiểm thử E2E với DB thật rồi xóa dữ liệu test.

#### Task 4: Sửa lỗi giao diện ô hình thức thanh toán
- **Mô tả:** Phần chọn thanh toán highlight 2 ô cùng lúc (ô COD giữ nền hồng dù đã bỏ chọn). Nguyên nhân: class `bg-brand-50` gắn cứng (không có `!`) trên ô COD không bao giờ bị JS gỡ (JS chỉ gỡ bản `!bg-brand-50`), và cách toggle phụ thuộc class Tailwind `!important` dễ mất khi build purge.
- **Thay đổi:**
  - `public/checkout.php` — Đưa ô COD về cùng base trung tính; thay JS bằng logic bám theo radio `:checked` (sự kiện `change`) → luôn chỉ 1 ô sáng; thêm CSS `.payment-option.active` (viền + nền hồng + tiêu đề màu thương hiệu) độc lập với bản build CSS.

### Vấn đề còn tồn đọng
- Chưa commit (người dùng có thể commit sau).
- Có thể cân nhắc gộp khách vãng lai trùng SĐT (hiện tạo mới mỗi đơn).

### File liên quan
- `src/helpers/functions.php`
- `public/products.php`, `public/checkout.php`, `public/order-success.php`
- `public/admin/{products,categories,customers,employees,accounts,invoices,import-invoices,warehouse}.php`
