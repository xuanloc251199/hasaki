# ĐẶC TẢ USE CASE — HỆ THỐNG HASAKI

> Bám sát theo báo cáo đồ án. Hệ thống gồm **9 use case trang quản trị** và **6 use case trang người dùng**.
> Tác nhân: **Admin** (quản trị viên), **Nhân viên**, **Khách hàng** (kể cả khách vãng lai), **SYSTEM** (hệ thống).

---

## A. USE CASE TRANG QUẢN TRỊ

### UC01 — Quản lý khách hàng
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Nhân viên |
| Mô tả / Mục đích | Cho phép thêm, sửa, xóa và tìm kiếm khách hàng |
| Tiền điều kiện | Đăng nhập thành công vào phần mềm |
| Hậu điều kiện | Dữ liệu khách hàng được cập nhật vào CSDL |

**Luồng sự kiện chính:**
1. Người dùng chọn chức năng Quản lý khách hàng.
2. SYSTEM lấy dữ liệu khách hàng từ CSDL và hiển thị.
3. Người dùng chọn **Thêm** → nhập thông tin khách hàng mới → SYSTEM kiểm tra dữ liệu.
4. Người dùng chọn **Sửa** → nhập thông tin mới → SYSTEM kiểm tra dữ liệu.
5. Người dùng chọn **Xóa** → SYSTEM hỏi xác nhận "Bạn có chắc chắn muốn xóa?".
6. Người dùng nhập từ khóa **Tìm kiếm** → SYSTEM hiển thị kết quả.

**Luồng phụ / ngoại lệ:**
- Dữ liệu hợp lệ → "Thành công" và lưu vào CSDL.
- Dữ liệu không hợp lệ → "Yêu cầu nhập đúng thông tin", nhập lại.
- Xác nhận xóa "Không" → đóng thông báo, quay lại danh sách.
- Tìm kiếm không khớp → trả về bảng trắng.

### UC02 — Quản lý sản phẩm
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Nhân viên |
| Mô tả / Mục đích | Thêm, sửa, xóa và tìm kiếm sản phẩm |
| Tiền điều kiện | Đăng nhập thành công |
| Hậu điều kiện | Dữ liệu sản phẩm được cập nhật vào CSDL |

**Luồng chính:** tương tự UC01 áp dụng cho sản phẩm (xem danh sách → Thêm / Sửa / Xóa / Tìm kiếm, SYSTEM kiểm tra dữ liệu ở mỗi thao tác).
**Luồng phụ:** Hợp lệ → "Thành công" + lưu CSDL; Không hợp lệ → "Yêu cầu nhập đúng thông tin"; Xác nhận xóa; Tìm kiếm không khớp → bảng trắng.

### UC03 — Quản lý hóa đơn bán
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Nhân viên |
| Mô tả / Mục đích | Xem, cập nhật trạng thái, xóa và tìm kiếm hóa đơn bán |
| Tiền điều kiện | Đăng nhập thành công |
| Hậu điều kiện | Trạng thái/đơn hàng được cập nhật vào CSDL |

**Luồng chính:** Xem danh sách đơn → xem chi tiết → cập nhật trạng thái (0 Chờ xác nhận → 1 Đang giao → 2 Hoàn thành / 3 Đã hủy) → xóa → tìm kiếm theo mã/SĐT/tên KH.
**Luồng phụ:** như mẫu CRUD chuẩn (hợp lệ/không hợp lệ/xác nhận xóa/tìm không khớp).

### UC04 — Quản lý hóa đơn nhập
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Nhân viên |
| Mô tả / Mục đích | Thêm (nhập kho), sửa, xóa và tìm kiếm hóa đơn nhập |
| Tiền điều kiện | Đăng nhập thành công |
| Hậu điều kiện | Hóa đơn nhập được lưu; **tồn kho được cộng tương ứng** |

**Luồng chính:**
1. Xem danh sách hóa đơn nhập.
2. **Thêm phiếu nhập**: chọn nhà cung cấp + danh sách sản phẩm (số lượng, giá nhập) → SYSTEM kiểm tra → lưu hóa đơn và **cộng tồn kho**.
3. **Sửa phiếu**: SYSTEM hoàn tồn kho cũ rồi áp tồn kho mới.
4. **Xóa**: hỏi xác nhận.
5. **Tìm kiếm** theo nhà cung cấp / SĐT.

**Luồng phụ:** hợp lệ → "Thành công" + lưu; không hợp lệ → yêu cầu nhập lại; xác nhận xóa.

### UC05 — Quản lý tài khoản
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Admin |
| Mô tả / Mục đích | Thêm, khóa/mở khóa, xóa và tìm kiếm tài khoản |
| Tiền điều kiện | Đăng nhập với quyền Admin |
| Hậu điều kiện | Tài khoản được cập nhật vào CSDL |

**Luồng chính:** Xem danh sách → Thêm → Sửa/khóa-mở → Xóa (xác nhận) → Tìm kiếm. SYSTEM kiểm tra dữ liệu mỗi thao tác.
**Luồng phụ:** mẫu CRUD chuẩn.

### UC06 — Quản lý nhân viên
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Admin |
| Mô tả / Mục đích | Thêm, sửa, xóa và tìm kiếm nhân viên |
| Tiền điều kiện | Đăng nhập với quyền Admin |
| Hậu điều kiện | Dữ liệu nhân viên được cập nhật vào CSDL |

**Luồng chính:** Xem danh sách → Thêm → Sửa → Xóa (xác nhận "Bạn có chắc chắn muốn xóa nhân viên này không") → Tìm kiếm.
**Luồng phụ:** mẫu CRUD chuẩn.

### UC07 — Quản lý kho hàng
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Nhân viên |
| Mô tả / Mục đích | Thêm, sửa, xóa, xem, tìm kiếm và cập nhật tồn kho mỹ phẩm |
| Tiền điều kiện | Đăng nhập thành công |
| Hậu điều kiện | Số lượng tồn kho được cập nhật vào CSDL |

**Luồng chính:** Xem kho → Thêm/Sửa số lượng tồn → Xóa (xác nhận) → Tìm kiếm.
**Luồng phụ:** hợp lệ → "Thành công" + lưu; không hợp lệ → nhập lại; xác nhận xóa "Có" → xóa mục được chọn.

### UC08 — Thống kê
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Admin |
| Mô tả / Mục đích | Truy xuất thống kê hoạt động của cửa hàng theo thời gian |
| Tiền điều kiện | Đăng nhập với quyền Admin |
| Hậu điều kiện | N/A |

**Luồng chính:** Mở trang thống kê → SYSTEM hiển thị các báo cáo: doanh thu theo ngày/tháng, sản phẩm bán chạy, nhiều đơn nhất, nhiều lượt xem, sản phẩm mới, phân bố trạng thái/hình thức thanh toán.
**Luồng phụ:** Không có.

### UC10 — Quản lý AI Chatbot
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Admin |
| Mô tả / Mục đích | Thêm, sửa, xóa, bật/tắt intent và cấu hình hành vi chatbot |
| Tiền điều kiện | Đăng nhập trang quản trị |
| Hậu điều kiện | Cấu hình/intent được lưu vào CSDL |

**Luồng chính:**
1. Mở "AI Chatbot" → SYSTEM hiển thị danh sách intent (tên, từ khóa, loại gợi ý, trạng thái).
2. **Thêm intent**: nhập tên, từ khóa (phân tách bằng `|`), câu trả lời, loại gợi ý, thứ tự → SYSTEM kiểm tra.
3. **Sửa intent** → cập nhật thông tin.
4. **Bật/tắt** một intent → SYSTEM đổi trạng thái kích hoạt.
5. **Xóa intent** → xác nhận.

**Luồng phụ:** dữ liệu hợp lệ → "Đã thêm/cập nhật"; không hợp lệ → "Tên intent/Từ khóa không được trống"; xác nhận xóa Có/Không.

---

## B. USE CASE TRANG NGƯỜI DÙNG

### UC01 — Quản lý đơn hàng
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Khách hàng |
| Mô tả / Mục đích | Xem và theo dõi đơn hàng của khách |
| Tiền điều kiện | Khách hàng đăng nhập thành công |
| Hậu điều kiện | N/A |

**Luồng chính:** Chọn "Đơn hàng của tôi" → SYSTEM hiển thị danh sách đơn (họ tên, địa chỉ, SĐT, trạng thái giao hàng, tổng tiền). Có thể **in / tải hóa đơn**.
**Luồng phụ:** Không có.

### UC02 — Xem sản phẩm
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Khách hàng / khách vãng lai |
| Mô tả / Mục đích | Xem danh sách & chi tiết sản phẩm |
| Tiền điều kiện | Truy cập website |
| Hậu điều kiện | N/A |

**Luồng chính:**
1. Khách xem sản phẩm ở trang chủ → SYSTEM hiển thị theo từng loại.
2. Chọn 1 sản phẩm → SYSTEM hiển thị trang chi tiết + sản phẩm liên quan.
3. Chọn đặt hàng → SYSTEM thêm sản phẩm vào giỏ.

**Luồng phụ:** Không có.

### UC03 — Quản lý giỏ hàng
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Khách hàng |
| Mô tả / Mục đích | Thêm, sửa số lượng, xóa sản phẩm trong giỏ |
| Tiền điều kiện | Truy cập website |
| Hậu điều kiện | Giỏ hàng (session) được cập nhật |

**Luồng chính:** Vào trang giỏ → Thêm sản phẩm → Sửa số lượng (SYSTEM kiểm tra) → Xóa (xác nhận) → Đặt hàng → SYSTEM hiển thị trang hóa đơn.
**Luồng phụ:** Số lượng hợp lệ → cập nhật; không hợp lệ → "Số lượng không hợp lệ"; xác nhận xóa Có/Không.

### UC04 — Đăng nhập
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Admin, Khách hàng |
| Mô tả / Mục đích | Đăng nhập hệ thống |
| Tiền điều kiện | Nhập thông tin đăng nhập |
| Hậu điều kiện | Phiên đăng nhập được khởi tạo |

**Luồng chính:** Mở trang đăng nhập → nhập thông tin → SYSTEM kiểm tra → thông báo thành công và chuyển trang chủ/dashboard.
**Luồng phụ:** Dữ liệu sai → thông báo và yêu cầu nhập lại.

### UC05 — Đăng ký
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Khách hàng |
| Mô tả / Mục đích | Tạo tài khoản khách hàng |
| Tiền điều kiện | Nhập thông tin cần thiết |
| Hậu điều kiện | Tài khoản + khách hàng được tạo |

**Luồng chính:** Mở trang đăng ký → nhập thông tin → SYSTEM kiểm tra → "Tạo tài khoản thành công" + chuyển trang đăng nhập.
**Luồng phụ:** Dữ liệu không hợp lệ → thông báo và yêu cầu nhập lại.

### UC06 — Trò chuyện với AI Chatbot
| Trường | Nội dung |
|--------|----------|
| Tác nhân chính | Khách hàng (kể cả khách vãng lai) |
| Mô tả / Mục đích | Tư vấn sản phẩm, hỏi chính sách, nhận gợi ý nhanh |
| Tiền điều kiện | Truy cập bất kỳ trang nào (không cần đăng nhập) |
| Hậu điều kiện | N/A |

**Luồng chính:**
1. Nhấn nút Chatbot → SYSTEM mở cửa sổ chat, gọi `chatbot.php?action=init` lấy lời chào + quick reply.
2. Khách nhập câu hỏi hoặc bấm gợi ý nhanh → SYSTEM hiển thị tin nhắn + hiệu ứng "đang trả lời".
3. SYSTEM chuẩn hóa câu hỏi (lower-case + khử dấu), duyệt intent đang kích hoạt, khớp từ khóa.
4. SYSTEM trả về câu trả lời kèm sản phẩm gợi ý (nếu có).
5. Khách bấm thẻ sản phẩm để mở chi tiết hoặc đóng chatbot.

**Luồng phụ:**
- Có intent khớp → trả lời của intent + sản phẩm theo loại gợi ý (none/search/featured/newest).
- Không khớp & fallback search bật → tìm sản phẩm theo từ ≥ 3 ký tự.
- Không tìm thấy → câu trả lời mặc định (fallback) + hướng dẫn/hotline.
- Mất kết nối → "Hiện không kết nối được tới máy chủ. Bạn thử lại sau nhé!".

---

📎 Sơ đồ Use Case: [usecase-quantri.png](../uml/png/usecase-quantri.png) · [usecase-nguoidung.png](../uml/png/usecase-nguoidung.png)
