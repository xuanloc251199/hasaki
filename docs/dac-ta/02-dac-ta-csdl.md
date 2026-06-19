# ĐẶC TẢ CƠ SỞ DỮ LIỆU — HASAKI

> CSDL `hasaki_db` (MySQL 8 / MariaDB, charset `utf8mb4`). Đối chiếu trực tiếp với `database/schema.sql`.
> Nhóm **lõi** (11 bảng) theo thiết kế báo cáo + nhóm **mở rộng** (đánh giá, chatbot, cấu hình, menu).
> Sơ đồ ERD: [erd-csdl.png](../uml/png/erd-csdl.png)

## Quy ước
- **PK** = khóa chính, **FK** = khóa ngoại, **U** = duy nhất (unique).

---

## 1. LoaiTaiKhoan — Loại tài khoản
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaLoaiTaiKhoan | INT | PK, AUTO_INCREMENT | Mã loại tài khoản |
| TenLoaiTaiKhoan | VARCHAR(50) | NOT NULL | Tên loại (Admin / Nhân viên / Khách hàng) |
| MoTa | VARCHAR(255) | NULL | Mô tả |

## 2. TaiKhoan — Tài khoản đăng nhập
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaTaiKhoan | INT | PK, AUTO_INCREMENT | Mã tài khoản |
| TenTaiKhoan | VARCHAR(100) | NOT NULL, U | Tên đăng nhập |
| MatKhau | VARCHAR(255) | NOT NULL | Mật khẩu (bcrypt hash) |
| LoaiTaiKhoan | INT | FK → LoaiTaiKhoan | Phân quyền |
| TrangThai | TINYINT | DEFAULT 1 | 1=hoạt động, 0=khóa |
| NgayTao | DATETIME | DEFAULT NOW | Ngày tạo |

## 3. NhanVien — Nhân viên
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaNV | INT | PK, AUTO_INCREMENT | Mã nhân viên |
| TenNV | VARCHAR(100) | NOT NULL | Họ tên |
| DiaChi | VARCHAR(255) | NULL | Địa chỉ |
| SDT | VARCHAR(15) | NULL | Số điện thoại |
| Email | VARCHAR(100) | NULL | Email |
| CMND | VARCHAR(20) | NOT NULL | CMND/CCCD |
| GioiTinh | VARCHAR(10) | NULL | Giới tính |
| AnhNhanVien | VARCHAR(255) | NULL | Ảnh đại diện |
| MaTaiKhoan | INT | FK → TaiKhoan, NULL, ON DELETE SET NULL | Tài khoản liên kết |

## 4. KhachHang — Khách hàng
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaKhachHang | INT | PK, AUTO_INCREMENT | Mã khách hàng |
| TenKhachHang | VARCHAR(100) | NOT NULL | Họ tên |
| DiaChi | VARCHAR(255) | NULL | Địa chỉ |
| SDT | VARCHAR(15) | NULL | Số điện thoại |
| Email | VARCHAR(100) | NULL | Email |
| GioiTinh | VARCHAR(10) | NULL | Giới tính |
| MaTaiKhoan | INT | FK → TaiKhoan, NULL, ON DELETE SET NULL | Tài khoản liên kết |
| NgayDangKy | DATETIME | DEFAULT NOW | Ngày đăng ký |

## 5. DanhMuc — Danh mục sản phẩm
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaDanhMuc | INT | PK, AUTO_INCREMENT | Mã danh mục |
| TenDanhMuc | VARCHAR(100) | NOT NULL | Tên danh mục |
| MoTa | VARCHAR(255) | NULL | Mô tả |
| AnhDanhMuc | VARCHAR(255) | NULL | Ảnh danh mục |

## 6. SanPham — Sản phẩm
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaSanPham | INT | PK, AUTO_INCREMENT | Mã sản phẩm |
| MaDanhMuc | INT | FK → DanhMuc, NOT NULL | Danh mục |
| TenSanPham | VARCHAR(255) | NOT NULL | Tên sản phẩm |
| ThuongHieu | VARCHAR(100) | NULL | Thương hiệu |
| XuatXu | VARCHAR(100) | NULL | Xuất xứ |
| DungTich | VARCHAR(50) | NULL | Dung tích |
| LoaiDa | VARCHAR(100) | NULL | Loại da phù hợp |
| SoLuong | INT | DEFAULT 0 | Số lượng tồn |
| GiaBan | DECIMAL(12,2) | NOT NULL | Giá bán |
| MauSac | VARCHAR(100) | NULL | Màu sắc |
| KichCo | VARCHAR(100) | NULL | Kích cỡ |
| MoTa | TEXT | NULL | Mô tả |
| ThanhPhan | TEXT | NULL | Thành phần |
| HuongDanSuDung | TEXT | NULL | Hướng dẫn sử dụng |
| BaoQuan | TEXT | NULL | Cách bảo quản |
| HanSuDung | VARCHAR(100) | NULL | Hạn sử dụng |
| HinhAnh | VARCHAR(255) | NOT NULL | Ảnh chính |
| AnhHover1, AnhHover2 | VARCHAR(255) | NULL | Ảnh phụ |
| Sale | VARCHAR(20) | NULL | % khuyến mãi |
| ThongBao | VARCHAR(255) | NULL | Nhãn thông báo |
| NgayTao | DATETIME | DEFAULT NOW | Ngày tạo |

## 7. HoaDonBan — Hóa đơn bán
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaHoaDon | INT | PK, AUTO_INCREMENT | Mã hóa đơn |
| NgayBan | DATETIME | DEFAULT NOW | Ngày đặt |
| ThanhTien | DECIMAL(12,2) | NOT NULL | Tổng tiền |
| MaKhachHang | INT | FK → KhachHang, NOT NULL | Khách mua |
| SDT | VARCHAR(15) | NULL | SĐT nhận hàng |
| Email | VARCHAR(100) | NULL | Email |
| DiaChiGH | VARCHAR(255) | NULL | Địa chỉ giao hàng |
| GhiChu | VARCHAR(255) | NULL | Ghi chú |
| HinhThucTT | VARCHAR(50) | DEFAULT 'COD' | COD / VNPay / MoMo / Bank |
| TrangThai | INT | DEFAULT 0 | 0=Chờ xác nhận, 1=Đang giao, 2=Hoàn thành, 3=Đã hủy |

## 8. ChiTietHoaDonBan — Chi tiết hóa đơn bán
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaChiTietHoaDon | INT | PK, AUTO_INCREMENT | Mã dòng |
| MaHoaDon | INT | FK → HoaDonBan, ON DELETE CASCADE | Hóa đơn |
| MaSanPham | INT | FK → SanPham | Sản phẩm |
| TenSanPham | VARCHAR(255) | NOT NULL | Tên SP (snapshot) |
| SoLuong | INT | NOT NULL | Số lượng |
| GiaBan | DECIMAL(12,2) | NOT NULL | Đơn giá |
| KichCo | VARCHAR(100) | NULL | Kích cỡ |
| MauSac | VARCHAR(100) | NULL | Màu sắc |

## 9. HoaDonNhap — Hóa đơn nhập
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaHoaDon | INT | PK, AUTO_INCREMENT | Mã hóa đơn nhập |
| NgayNhap | DATETIME | DEFAULT NOW | Ngày nhập |
| ThanhTien | DECIMAL(12,2) | NULL | Tổng tiền nhập |
| TenNCC | VARCHAR(100) | NOT NULL | Nhà cung cấp |
| SDT | VARCHAR(15) | NULL | SĐT NCC |
| MaNV | INT | FK → NhanVien, NULL | Nhân viên nhập |
| Email | VARCHAR(100) | NULL | Email NCC |
| DiaChiLayHang | VARCHAR(255) | NULL | Địa chỉ lấy hàng |

## 10. ChiTietHoaDonNhap — Chi tiết hóa đơn nhập
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaChiTietHoaDon | INT | PK, AUTO_INCREMENT | Mã dòng |
| MaHoaDon | INT | FK → HoaDonNhap, ON DELETE CASCADE | Hóa đơn nhập |
| MaSanPham | INT | FK → SanPham | Sản phẩm |
| TenSanPham | VARCHAR(255) | NOT NULL | Tên SP |
| MauSac | VARCHAR(100) | NULL | Màu sắc |
| KichCo | VARCHAR(100) | NULL | Kích cỡ |
| SoLuong | INT | NOT NULL | Số lượng nhập |
| GiaNhap | DECIMAL(12,2) | NOT NULL | Giá nhập |
| AnhSanPham | VARCHAR(255) | NULL | Ảnh |

## 11. KhoHang — Kho hàng
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaKho | INT | PK, AUTO_INCREMENT | Mã kho |
| MaSanPham | INT | FK → SanPham, ON DELETE CASCADE | Sản phẩm |
| TenSanPham | VARCHAR(255) | NOT NULL | Tên SP |
| SoLuongCon | INT | DEFAULT 0 | Số lượng còn |
| NgayNhap | DATE | NULL | Ngày nhập gần nhất |
| LoaiSanPham | VARCHAR(100) | NULL | Loại |
| AnhSanPham | VARCHAR(255) | NULL | Ảnh |

---

## Nhóm bảng mở rộng

## 12. DanhGia — Đánh giá sản phẩm
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaDanhGia | INT | PK, AUTO_INCREMENT | Mã đánh giá |
| MaSanPham | INT | FK → SanPham, ON DELETE CASCADE | Sản phẩm |
| MaKhachHang | INT | FK → KhachHang, NULL, ON DELETE SET NULL | Khách đánh giá |
| TenNguoiDanhGia | VARCHAR(100) | NOT NULL | Tên người đánh giá |
| SoSao | TINYINT | CHECK 1..5 | Số sao |
| TieuDe | VARCHAR(255) | NULL | Tiêu đề |
| NoiDung | TEXT | NOT NULL | Nội dung |
| HuuIch | INT | DEFAULT 0 | Lượt "hữu ích" |
| DaXacThucMua | TINYINT | DEFAULT 0 | Đã mua hàng |
| NgayDanhGia | DATETIME | DEFAULT NOW | Ngày |

## 13. ChatbotIntent — Ý định chatbot
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| MaIntent | INT | PK, AUTO_INCREMENT | Mã intent |
| TenIntent | VARCHAR(100) | NOT NULL | Tên |
| TuKhoa | TEXT | NOT NULL | Từ khóa (ngăn bằng `|`) |
| CauTraLoi | TEXT | NOT NULL | Câu trả lời |
| LoaiGoiY | VARCHAR(20) | DEFAULT 'none' | none/search/featured/newest |
| GiaTriGoiY | VARCHAR(100) | NULL | Từ khóa gợi ý |
| Link | VARCHAR(255) | NULL | Liên kết |
| ThuTu | INT | DEFAULT 0 | Thứ tự |
| KichHoat | TINYINT | DEFAULT 1 | Bật/tắt |
| LaQuickReply | TINYINT | DEFAULT 0 | Hiển thị gợi ý nhanh |
| NgayCapNhat | DATETIME | ON UPDATE NOW | Cập nhật |

## 14. ChatbotConfig — Cấu hình chatbot
| Thuộc tính | Kiểu | Ràng buộc | Mô tả |
|-----------|------|-----------|-------|
| ConfigKey | VARCHAR(50) | PK | Khóa cấu hình (greeting, fallback, bot_name…) |
| ConfigValue | TEXT | NULL | Giá trị |
| GhiChu | VARCHAR(255) | NULL | Ghi chú |

## 15. CauHinh — Cấu hình website (key-value)
Lưu các thiết lập website theo nhóm (general, contact, social, banner, hero, footer, seo). Khóa chính là khóa cấu hình; giá trị dạng text. Ví dụ: `site_name`, `contact_phone`, `contact_address`, `contact_email`.

## 16. MenuItem — Menu động
Lưu các mục menu theo vị trí (header, footer). Gồm: nhãn, liên kết, thứ tự, vị trí hiển thị.

---

## Tổng hợp quan hệ khóa ngoại
| Bảng con | Bảng cha | Bội số | ON DELETE |
|----------|----------|--------|-----------|
| TaiKhoan | LoaiTaiKhoan | n–1 | — |
| NhanVien | TaiKhoan | 0..1–1 | SET NULL |
| KhachHang | TaiKhoan | 0..1–1 | SET NULL |
| SanPham | DanhMuc | n–1 | — |
| HoaDonBan | KhachHang | n–1 | — |
| ChiTietHoaDonBan | HoaDonBan | n–1 | CASCADE |
| ChiTietHoaDonBan | SanPham | n–1 | — |
| HoaDonNhap | NhanVien | n–1 | — |
| ChiTietHoaDonNhap | HoaDonNhap | n–1 | CASCADE |
| ChiTietHoaDonNhap | SanPham | n–1 | — |
| KhoHang | SanPham | n–1 | CASCADE |
| DanhGia | SanPham | n–1 | CASCADE |
| DanhGia | KhachHang | n–1 | SET NULL |
