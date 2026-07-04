# Session Log

Lịch sử các phiên làm việc với dự án Hasaki.

---

## [2026-07-04] — Thanh toán chuyển khoản ngân hàng bằng mã QR

### Mục tiêu
Thêm hình thức thanh toán chuyển khoản ngân hàng quét mã QR: chỉ dùng **ảnh QR tĩnh** (admin tự upload), **không tích hợp API cổng thanh toán thật**. Ảnh QR + thông tin ngân hàng quản lý được trong admin; đơn theo luồng 2 bước (đặt hàng → trang QR → xác nhận đã chuyển khoản → chờ shop kiểm tra).

### Changelog chi tiết theo task

#### Task 1: Cấu hình thanh toán quản lý trong admin (Settings)
- **Mô tả:** Thay vì hằng số cứng, đưa toàn bộ thông tin ngân hàng + ảnh QR vào bảng `CauHinh` (nhóm mới `thanhtoan`), sửa được ngay trong **Admin → Cài đặt → tab "Thanh toán"** (tận dụng cơ chế upload ảnh sẵn có).
- **Thay đổi:**
  - `database/migrate_v6.sql` (mới) — thêm 6 khoá: `bank_transfer_enable` (boolean), `bank_qr_image` (image/upload), `bank_name`, `bank_account_no`, `bank_account_name`, `bank_transfer_note` (tiền tố nội dung CK). Dùng `ON DUPLICATE KEY UPDATE` nên chạy lại an toàn.
  - `public/admin/settings.php` — thêm `groupMeta['thanhtoan']` để hiện tab "Thanh toán" (icon `fa-qrcode`).
  - `src/helpers/functions.php` — `bank_transfer_enabled()` (bật khi admin bật **và** đã upload ảnh QR), `payment_method_label()` (nhãn tiếng Việt cho mã `HinhThucTT`). Gỡ helper `vietqr_image_url()` (bản thử đầu tiên dùng img.vietqr.io — đã bỏ theo yêu cầu chỉ dùng ảnh tĩnh).
  - `config/config.php` — không thêm hằng ngân hàng (đã chuyển hết sang Settings).
- **Ghi chú:** Chạy migration 1 lần: `mysql -uroot --default-character-set=utf8mb4 < database/migrate_v6.sql`. Mặc định `bank_qr_image` rỗng → phương thức QR **ẩn** cho tới khi admin upload ảnh.

#### Task 2: Luồng thanh toán QR 2 bước + bắt buộc xác nhận
- **Mô tả:** Luồng: chọn "Chuyển khoản QR" → **Đặt hàng ngay** (tạo đơn, trạng thái *Chờ xác nhận*) → tự chuyển sang trang QR → khách chuyển khoản rồi bấm **"Tôi đã chuyển khoản"** → màn hình **"Đã ghi nhận, đang chờ kiểm tra"**. Không có cổng thật: xác nhận là khách tự cam kết, admin đối chiếu thủ công.
- **Thay đổi:**
  - `public/bank-payment.php` (mới) — trang QR: kiểm soát truy cập như order-success (thành viên xem đơn của mình; khách vãng lai chỉ xem đơn vừa đặt trong session). Hiện ảnh QR + STK/chủ TK + số tiền + nội dung CK `HASAKI DH{mã đơn}` + nút xác nhận. POST `action=confirm` → ghi dấu vào đơn rồi hiện màn hình chờ kiểm tra. Không phải đơn BANK → redirect về order-success.
  - `public/checkout.php` — whitelist `payment` (chỉ nhận `BANK` khi bật); bỏ ô tick + QR inline của bản trước, thay bằng dòng nhắc; BANK → sau khi tạo đơn thì `redirect(bank-payment.php?id=...)` thay vì order-success; sửa nhánh tạo hồ sơ khách để **không tạo bản ghi khách rác** khi có lỗi.
  - `src/Business/InvoiceBLL.php` — `confirmBankTransfer()` (idempotent: append `[Khách đã xác nhận chuyển khoản lúc dd/mm/YYYY HH:MM]` vào `GhiChu`, không ghi trùng), `isBankConfirmed()`, hằng `BANK_CONFIRM_MARK`. Đơn giữ trạng thái *Chờ xác nhận* (0).
  - `src/DataAccess/InvoiceDAL.php` — `updateNote()`.
  - `public/order-success.php` — dùng ảnh QR + thông tin từ Settings + `payment_method_label()`; câu chữ khối chuyển khoản để trung tính (trang chi tiết, không phải nơi xác nhận).
  - `public/assets/css/tailwind.css` — build lại (thêm class teal/w-52/border-dashed…).
- **Ghi chú:** Đã kiểm thử E2E với DB thật (đặt đơn → trang QR → xác nhận → ghi chú lưu đúng UTF-8; bấm lại không ghi trùng; tắt chức năng thì server từ chối `payment=BANK`, tự về COD) rồi **xóa sạch dữ liệu test**. Admin đối chiếu dấu xác nhận trong ghi chú đơn rồi tự cập nhật trạng thái sang *Hoàn thành* khi nhận đủ tiền.

### File liên quan
- `database/migrate_v6.sql`, `public/bank-payment.php` (mới)
- `public/checkout.php`, `public/order-success.php`, `public/admin/settings.php`
- `src/Business/InvoiceBLL.php`, `src/DataAccess/InvoiceDAL.php`, `src/helpers/functions.php`
- `public/assets/css/tailwind.css`

---

## [2026-06-30] — Tài liệu lý thuyết dự án & dọn thay đổi tồn đọng

### Mục tiêu
Ghi lại toàn bộ lý thuyết dự án (ngôn ngữ, frontend, backend, database, kiến trúc) thành tài liệu; commit các thay đổi code còn tồn trong working tree.

### Changelog chi tiết theo task

#### Task 1: Tạo tài liệu lý thuyết dự án
- **Mô tả:** Tổng hợp nền tảng lý thuyết của dự án để phục vụ báo cáo/ôn tập, đối chiếu trực tiếp với mã nguồn (không chỉ chép từ README).
- **Thay đổi:**
  - `docs/LY-THUYET-DU-AN.md` (mới) — 10 phần: tổng quan, ngôn ngữ lập trình, frontend (HTML5 + SCSS + Tailwind v3 + Vanilla JS + Font Awesome), backend (PHP 8 thuần, kiến trúc 3-Layer, mẫu Singleton/DAO), database (MySQL 8 + PDO, 16 bảng), bảo mật, các luồng nghiệp vụ tiêu biểu, môi trường, tổ chức thư mục, tóm tắt stack.
- **Ghi chú:** Thư mục `docs/` đang bị gitignore (`.gitignore` dòng 27) → **tài liệu chỉ tồn tại local, không push lên repo** (theo quyết định của người dùng). Có thể `git add -f` sau nếu muốn đưa lên.
- **Phát hiện khi đối chiếu code:** frontend thực tế là **hybrid SCSS + Tailwind** (README chỉ ghi SCSS); có thêm module chatbot rule-based, đánh giá sản phẩm, cấu hình & menu động; logic **trừ kho khi đơn *Hoàn thành*** (không trừ lúc đặt) — đúng theo `InvoiceBLL.php`.

#### Task 2: Commit các thay đổi code tồn đọng
- **Mô tả:** 3 file đã sửa từ trước nhưng chưa commit.
- **Thay đổi:**
  - `src/helpers/functions.php` — định dạng lại theo PSR-12 (đưa `{` xuống dòng, chuẩn hoá thụt lề); **không đổi logic**.
  - `public/products.php` — phân trang storefront **12 → 15 sản phẩm/trang**.
  - `public/assets/css/tailwind.css` — build lại CSS.
- **Ghi chú:** Commit `5bed389`, đã push lên `origin/master`.

### File liên quan
- `docs/LY-THUYET-DU-AN.md` (local-only)
- `src/helpers/functions.php`, `public/products.php`, `public/assets/css/tailwind.css`

---

## [2026-06-27] — Gửi cảnh báo tồn kho qua email SMTP (Gmail)

### Mục tiêu
Chuyển cơ chế gửi email cảnh báo tồn kho từ hàm `mail()` của PHP (không chạy được trên localhost vì thiếu MTA) sang gửi trực tiếp qua SMTP Gmail/Google Workspace bằng App Password.

### Changelog chi tiết theo task

#### Task 1: Cấu hình tài khoản gửi email
- **Mô tả:** Tách thông tin SMTP ra file config riêng để không lộ mật khẩu trong trang Cài đặt và không cần migration DB.
- **Thay đổi:**
  - `config/mail.php` (mới) — Các hằng: `MAIL_ENABLE`, `SMTP_HOST` (`smtp.gmail.com`), `SMTP_PORT` (`465`), `SMTP_SECURE` (`ssl`), `SMTP_USER`, `SMTP_PASS` (App Password, tự loại khoảng trắng), `SMTP_FROM_NAME`, `ADMIN_ALERT_EMAIL` (địa chỉ nhận cảnh báo mặc định).
  - `config/config.php` — `require_once config/mail.php` (đặt trước `database.php`; SITE_NAME đã định nghĩa phía trên nên dùng được trong mail.php).
- **Ghi chú:** Tài khoản gửi là `lethikimphung.d22ctc1@muce.edu.vn`. Gmail bắt buộc địa chỉ From khớp tài khoản xác thực.

#### Task 2: SMTP client + định tuyến gửi mail
- **Mô tả:** Viết lại mailer để gửi qua SMTP, giữ `mail()` làm dự phòng, không nem exception để không làm hỏng luồng checkout.
- **Thay đổi:**
  - `src/helpers/mailer.php` — Thêm `smtp_send_mail()` (SMTP thuần PHP qua `stream_socket_client`: EHLO → AUTH LOGIN → MAIL FROM → RCPT TO → DATA; hỗ trợ SSL cổng 465 và STARTTLS cổng 587; body base64 UTF-8 để không lỗi tiếng Việt) + helper `smtp_cmd()`/`smtp_expect()` (đọc phản hồi nhiều dòng). `send_html_mail()` ưu tiên SMTP khi đã cấu hình, fallback `php_mail_fallback()`. `admin_alert_recipient()` thêm fallback cuối về hằng `ADMIN_ALERT_EMAIL`.
- **Ghi chú:** Đã test gửi thật → nhận **SENT OK**. Lỗi gửi được ghi `error_log` để debug.

#### Task 3: Cập nhật ghi chú cấu hình
- **Thay đổi:**
  - `database/migrate_v5.sql` — Sửa GhiChu của `low_stock_email_enable` và `admin_alert_email` cho khớp (gửi qua SMTP Gmail thay vì cần MTA cho `mail()`).

### Vấn đề còn tồn đọng
- App Password để dạng plaintext trong `config/mail.php` — chấp nhận được cho đồ án; không commit lên repo công khai nếu nhạy cảm.
- Chưa commit.

### File liên quan
- `config/mail.php`, `config/config.php`
- `src/helpers/mailer.php`
- `database/migrate_v5.sql`
- `src/Business/NotificationBLL.php` (luồng gọi, không đổi)

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
