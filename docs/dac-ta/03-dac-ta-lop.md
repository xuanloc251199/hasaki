# ĐẶC TẢ LỚP — HASAKI

> Hệ thống theo kiến trúc **3 lớp**: Controller/View → Business (BLL) → DataAccess (DAL) → MySQL.
> Sơ đồ: [class-thucthe.png](../uml/png/class-thucthe.png) (lớp thực thể) · [class-chitiet.png](../uml/png/class-chitiet.png) (lớp chi tiết 3 lớp).

---

## A. LỚP THỰC THỂ (Domain / Entity)

Các lớp thực thể ánh xạ 1–1 với bảng CSDL (xem [đặc tả CSDL](02-dac-ta-csdl.md) để biết kiểu dữ liệu & ràng buộc).

| Lớp | Thuộc tính chính | Quan hệ |
|-----|------------------|---------|
| **LoaiTaiKhoan** | MaLoaiTaiKhoan, TenLoaiTaiKhoan, MoTa | 1 — n TaiKhoan |
| **TaiKhoan** | MaTaiKhoan, TenTaiKhoan, MatKhau, LoaiTaiKhoan, TrangThai | n — 1 LoaiTaiKhoan; 1 — 0..1 NhanVien/KhachHang |
| **NhanVien** | MaNV, TenNV, DiaChi, SDT, Email, CMND, GioiTinh, MaTaiKhoan | n — 1 TaiKhoan; 1 — n HoaDonNhap |
| **KhachHang** | MaKhachHang, TenKhachHang, DiaChi, SDT, Email, MaTaiKhoan | n — 1 TaiKhoan; 1 — n HoaDonBan, DanhGia |
| **DanhMuc** | MaDanhMuc, TenDanhMuc, MoTa, AnhDanhMuc | 1 — n SanPham |
| **SanPham** | MaSanPham, MaDanhMuc, TenSanPham, GiaBan, SoLuong, HinhAnh… | n — 1 DanhMuc; 1 — n các chi tiết, kho, đánh giá |
| **HoaDonBan** | MaHoaDon, NgayBan, ThanhTien, MaKhachHang, HinhThucTT, TrangThai | n — 1 KhachHang; 1 — n ChiTietHoaDonBan |
| **ChiTietHoaDonBan** | MaChiTietHoaDon, MaHoaDon, MaSanPham, SoLuong, GiaBan | n — 1 HoaDonBan, SanPham |
| **HoaDonNhap** | MaHoaDon, NgayNhap, ThanhTien, TenNCC, MaNV | n — 1 NhanVien; 1 — n ChiTietHoaDonNhap |
| **ChiTietHoaDonNhap** | MaChiTietHoaDon, MaHoaDon, MaSanPham, SoLuong, GiaNhap | n — 1 HoaDonNhap, SanPham |
| **KhoHang** | MaKho, MaSanPham, SoLuongCon, NgayNhap | n — 1 SanPham |
| **DanhGia** | MaDanhGia, MaSanPham, MaKhachHang, SoSao, NoiDung | n — 1 SanPham, KhachHang |

---

## B. LỚP CHI TIẾT (3 lớp)

### B.1. DataAccess Layer (DAL)
Truy xuất CSDL bằng PDO prepared statement. Tất cả kế thừa **BaseDAL**.

| Lớp | Phương thức tiêu biểu |
|-----|----------------------|
| **BaseDAL** *(abstract)* | `__construct()` khởi tạo `$db : PDO` (singleton) |
| **ProductDAL** | getAll, getById, getByCategory, search, getFeatured, getNewest, create, update, delete, **decreaseStock**, **increaseStock** |
| **CategoryDAL** | getAll, getById, create, update, delete |
| **CustomerDAL** | getAll, getById, getByAccount, search, insert, update, delete, growthByMonth |
| **EmployeeDAL** | getAll, getById, getByAccount, search, create, update, delete |
| **AccountDAL** | getAll, getById, findByUsername, create, update, delete, setStatus |
| **InvoiceDAL** | getAll, getById, getByCustomer, getDetails, search, create, updateStatus, delete, revenueTrend, revenueByMonth, topProducts, revenueByCategory, statusDistribution, paymentDistribution, averageOrderValue, ordersByHour |
| **ImportInvoiceDAL** | getAll, getById, getDetails, search, create, **update**, delete |
| **WarehouseDAL** | getAll, getById, search, update, delete, **addStock**, **reduceStock** |
| **ReviewDAL** | getByProduct, getAverage, getDistribution, insert, markHelpful |
| **ChatbotDAL** | getActiveIntents, getAllIntents, getQuickReplies, insertIntent, updateIntent, deleteIntent, toggleIntent, getConfig, setConfig |
| **SettingDAL** | getAllAsMap, get, set, setMany |
| **MenuDAL** | getByPosition, create, update, delete |

### B.2. Business Layer (BLL)
Kiểm tra dữ liệu, xử lý nghiệp vụ, gọi DAL.

| Lớp | Phương thức tiêu biểu |
|-----|----------------------|
| **AuthBLL** | login, register, changePassword, logout |
| **CartBLL** | add, update, remove, clear, getAll, getTotal, getCount |
| **ProductBLL** | getAll, getById, getByCategory, search, getRelated, create, update, delete |
| **CategoryBLL** | getAll, getById, create, update, delete (kèm validate) |
| **CustomerBLL** | getAll, getById, getByAccount, create, update, delete |
| **EmployeeBLL** | getAll, getById, create, update, delete |
| **AccountBLL** | getAll, getById, create, update, delete, lock, unlock, resetPassword |
| **InvoiceBLL** | **checkout**, getById, getDetails, getByCustomer, updateStatus, delete, statusLabel, + các hàm thống kê |
| **ImportInvoiceBLL** | **createImport**, **updateImport**, getAll, getById, getDetails, search, delete |
| **ReviewBLL** | getByProduct, getAverage, getDistribution, create, markHelpful |
| **ChatbotBLL** | **reply**, adminListIntents, adminCreateIntent, adminUpdateIntent, adminDeleteIntent, adminToggleIntent, buildReply |
| **SettingBLL** | get, getAllGrouped, set, setMany (có cache) |
| **MenuBLL** | getByPosition, create, update, delete |

### B.3. Helpers
- **functions.php**: `e()`, `url()`, `asset()`, `format_currency()`, `format_date()`, `format_datetime()`, `is_logged_in()`, `is_admin()`, `is_employee()`, `require_login()`, `require_admin()`, `flash_*()`, `setting()`, `menu()`, `cart_count()`, `cart_total()`, `csrf_token()`, `verify_csrf()`.
- **upload.php**: xử lý upload ảnh (validate MIME, kích thước, lưu vào `assets/images/uploads/`).

---

## C. Nghiệp vụ tiêu biểu
- **InvoiceBLL::checkout** — tạo hóa đơn bán + chi tiết trong transaction, sau đó `decreaseStock` cho từng sản phẩm.
- **ImportInvoiceBLL::createImport / updateImport** — tạo/sửa phiếu nhập, cộng/hoàn tồn kho (`increaseStock`/`decreaseStock` + `addStock`/`reduceStock`).
- **AuthBLL::login** — xác thực `password_verify` với hash bcrypt, khởi tạo session.
- **ChatbotBLL::reply** — chuẩn hóa câu hỏi, khớp intent, fallback tìm sản phẩm.
