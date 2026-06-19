# UML & Đặc tả — HASAKI (FULL theo báo cáo)

**72 sơ đồ** vẽ lại đầy đủ theo báo cáo đồ án. Nguồn PlantUML ở `src/`, ảnh ở `png/` và `svg/`.
Mỗi sơ đồ có tên file trùng nhau ở 3 thư mục: `src/<tên>.puml`, `png/<tên>.png`, `svg/<tên>.svg`.

## 1. Tổng quát & Lớp (5)
| Tên file | Nội dung |
|----------|----------|
| 01-usecase-quantri | Use case tổng quát — trang quản trị |
| 02-usecase-nguoidung | Use case tổng quát — trang người dùng |
| 03-class-thucthe | Biểu đồ lớp thực thể (domain) |
| 04-erd-csdl | Sơ đồ quan hệ thực thể (ERD) |
| 05-class-chitiet | Biểu đồ lớp chi tiết (kiến trúc 3 lớp) |

## 2. Use case chi tiết — Trang quản trị (10)
`uc-a01-khachhang`, `uc-a02-sanpham`, `uc-a03-hoadonban`, `uc-a04-hoadonnhap`,
`uc-a05-taikhoan`, `uc-a06-nhanvien`, `uc-a07-khohang`, `uc-a08-thongke`,
`uc-a09-truyxuat`, `uc-a10-chatbot`

## 3. Use case chi tiết — Trang người dùng (6)
`uc-u01-donhang`, `uc-u02-xemsanpham`, `uc-u03-giohang`, `uc-u04-dangnhap`,
`uc-u05-dangky`, `uc-u06-chatbot`

## 4. Biểu đồ tuần tự (51) — đánh số theo báo cáo (Hình 30–80)
**Người dùng:** `sq-30-dangnhap`, `sq-31-dangky`, `sq-32-doimatkhau`, `sq-33-quanly-giohang`,
`sq-34-sua-giohang`, `sq-35-xoa-giohang`, `sq-36-dathang`, `sq-37-timkiem-sanpham`,
`sq-38-xem-sanpham`, `sq-39-doc-tintuc`

**Danh mục:** `sq-40-manage`…`sq-44-search-danhmuc`
**Sản phẩm:** `sq-45-manage`…`sq-49-search-sanpham`
**Khách hàng:** `sq-50-manage`…`sq-54-search-khachhang`
**Hóa đơn bán:** `sq-55-manage`…`sq-59-search-hoadonban`
**Hóa đơn nhập:** `sq-60-manage`…`sq-64-search-hoadonnhap`
**Nhân viên:** `sq-65-manage`…`sq-69-search-nhanvien`
**Tài khoản:** `sq-70-manage`…`sq-74-search-taikhoan`, `sq-75-khoa-taikhoan`, `sq-76-xoa-taikhoan2`
**Kho & Chatbot:** `sq-77-them-vao-gio`, `sq-78-timkiem-kho`, `sq-79-chatbot`, `sq-80-them-intent`

(Mỗi phân hệ CRUD gồm 5 sơ đồ: quản lý / thêm / sửa / xóa / tìm kiếm — `manage/add/edit/delete/search`.)

## Tài liệu đặc tả
- [Đặc tả Use Case](../dac-ta/01-dac-ta-usecase.md)
- [Đặc tả Cơ sở dữ liệu](../dac-ta/02-dac-ta-csdl.md)
- [Đặc tả Lớp](../dac-ta/03-dac-ta-lop.md)

## Vẽ lại (render) sơ đồ
Yêu cầu: Java + PlantUML + Graphviz. Toàn bộ file được sinh bởi `_gen.py`.

```bash
cd docs/uml
python _gen.py                       # sinh lại các file .puml
JAR="C:/Users/<user>/.vscode/extensions/jebbs.plantuml-2.18.1/plantuml.jar"
java -jar "$JAR" -charset UTF-8 -tpng -o "../png" "src/*.puml"
java -jar "$JAR" -charset UTF-8 -tsvg -o "../svg" "src/*.puml"
```
