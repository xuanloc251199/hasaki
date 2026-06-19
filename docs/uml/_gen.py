# -*- coding: utf-8 -*-
"""Sinh toàn bộ file PlantUML use case chi tiết + tuần tự theo báo cáo HASAKI."""
import os
SRC = os.path.join(os.path.dirname(__file__), 'src')
os.makedirs(SRC, exist_ok=True)

UC_HEAD = """@startuml {name}
left to right direction
skinparam shadowing false
skinparam packageStyle rectangle
skinparam actorStyle awesome
skinparam usecase {{
  BackgroundColor #FFF0F3
  BorderColor #FF5E8E
  ArrowColor #444444
}}
title {title}
"""

SEQ_HEAD = """@startuml {name}
title {title}
skinparam shadowing false
skinparam sequence {{
  ArrowColor #444444
  LifeLineBorderColor #FF5E8E
  ParticipantBorderColor #FF5E8E
  ParticipantBackgroundColor #FFF0F3
}}
"""

def write(name, content):
    with open(os.path.join(SRC, name + '.puml'), 'w', encoding='utf-8') as f:
        f.write(content)

# ---------- USE CASE CHI TIẾT ----------
def usecase(name, title, actors, module, ops, login=True):
    s = UC_HEAD.format(name=name, title=title)
    for i, a in enumerate(actors):
        s += 'actor "{}" as ACT{}\n'.format(a, i)
    s += 'rectangle "{}" {{\n'.format(module)
    for j, op in enumerate(ops):
        s += '  usecase "{}" as U{}\n'.format(op, j)
    if login:
        s += '  usecase "Đăng nhập" as LOGIN\n'
    s += '}\n'
    for i in range(len(actors)):
        for j in range(len(ops)):
            s += 'ACT{} -- U{}\n'.format(i, j)
    if login:
        for j in range(len(ops)):
            s += 'U{} ..> LOGIN : <<include>>\n'.format(j)
    s += '@enduml\n'
    write(name, s)

# Admin (UC01-UC10)
usecase('uc-a01-khachhang', 'Use case chi tiết - Quản lý khách hàng (UC01)', ['Nhân viên'], 'Quản lý khách hàng',
        ['Thêm khách hàng', 'Sửa khách hàng', 'Xóa khách hàng', 'Tìm kiếm khách hàng'])
usecase('uc-a02-sanpham', 'Use case chi tiết - Quản lý sản phẩm (UC02)', ['Nhân viên'], 'Quản lý sản phẩm',
        ['Thêm sản phẩm', 'Sửa sản phẩm', 'Xóa sản phẩm', 'Tìm kiếm sản phẩm'])
usecase('uc-a03-hoadonban', 'Use case chi tiết - Quản lý hóa đơn bán (UC03)', ['Nhân viên'], 'Quản lý hóa đơn bán',
        ['Xem hóa đơn', 'Cập nhật trạng thái', 'Xóa hóa đơn', 'Tìm kiếm hóa đơn'])
usecase('uc-a04-hoadonnhap', 'Use case chi tiết - Quản lý hóa đơn nhập (UC04)', ['Nhân viên'], 'Quản lý hóa đơn nhập',
        ['Thêm phiếu nhập', 'Sửa phiếu nhập', 'Xóa phiếu nhập', 'Tìm kiếm phiếu nhập'])
usecase('uc-a05-taikhoan', 'Use case chi tiết - Quản lý tài khoản (UC05)', ['Admin'], 'Quản lý tài khoản',
        ['Thêm tài khoản', 'Sửa tài khoản', 'Xóa tài khoản', 'Khóa/Mở tài khoản', 'Tìm kiếm tài khoản'])
usecase('uc-a06-nhanvien', 'Use case chi tiết - Quản lý nhân viên (UC06)', ['Admin'], 'Quản lý nhân viên',
        ['Thêm nhân viên', 'Sửa nhân viên', 'Xóa nhân viên', 'Tìm kiếm nhân viên'])
usecase('uc-a07-khohang', 'Use case chi tiết - Quản lý kho hàng (UC07)', ['Nhân viên'], 'Quản lý kho hàng',
        ['Thêm vào kho', 'Sửa tồn kho', 'Xóa khỏi kho', 'Xem kho', 'Tìm kiếm', 'Xuất kho'])
usecase('uc-a08-thongke', 'Use case chi tiết - Thống kê (UC08)', ['Admin'], 'Thống kê',
        ['Thống kê theo tháng', 'Thống kê theo quý', 'Thống kê theo năm', 'Thống kê theo mặt hàng'])
usecase('uc-a09-truyxuat', 'Use case chi tiết - Truy xuất thông tin (UC09)', ['Admin'], 'Truy xuất thông tin',
        ['Sản phẩm bán chạy nhất', 'Nhiều đơn đặt nhất', 'Nhiều lượt tương tác nhất', 'Doanh thu cao nhất'])
usecase('uc-a10-chatbot', 'Use case chi tiết - Quản lý AI Chatbot (UC10)', ['Admin'], 'Quản lý AI Chatbot',
        ['Thêm intent', 'Sửa intent', 'Xóa intent', 'Bật/tắt intent', 'Cấu hình chatbot'])

# User (UC01-UC06)
usecase('uc-u01-donhang', 'Use case chi tiết - Quản lý đơn hàng (UC01)', ['Khách hàng'], 'Quản lý đơn hàng',
        ['Xem danh sách đơn', 'Xem chi tiết đơn', 'In / Tải hóa đơn'])
usecase('uc-u02-xemsanpham', 'Use case chi tiết - Xem sản phẩm (UC02)', ['Khách hàng'], 'Xem sản phẩm',
        ['Xem danh sách sản phẩm', 'Xem chi tiết sản phẩm', 'Lọc theo danh mục', 'Xem đánh giá'], login=False)
usecase('uc-u03-giohang', 'Use case chi tiết - Quản lý giỏ hàng (UC03)', ['Khách hàng'], 'Quản lý giỏ hàng',
        ['Thêm vào giỏ', 'Sửa số lượng', 'Xóa khỏi giỏ', 'Đặt hàng'])
usecase('uc-u04-dangnhap', 'Use case chi tiết - Đăng nhập (UC04)', ['Admin', 'Khách hàng'], 'Đăng nhập',
        ['Nhập thông tin đăng nhập', 'Xác thực tài khoản'], login=False)
usecase('uc-u05-dangky', 'Use case chi tiết - Đăng ký (UC05)', ['Khách hàng'], 'Đăng ký',
        ['Nhập thông tin đăng ký', 'Tạo tài khoản'], login=False)
usecase('uc-u06-chatbot', 'Use case chi tiết - Trò chuyện với AI Chatbot (UC06)', ['Khách hàng'], 'Trò chuyện với AI Chatbot',
        ['Mở chatbot', 'Gửi câu hỏi', 'Chọn gợi ý nhanh', 'Xem sản phẩm gợi ý'], login=False)

# ---------- TUẦN TỰ ----------
def seq(name, title, body):
    write(name, SEQ_HEAD.format(name=name, title=title) + body + '\n@enduml\n')

def parts_admin(view, bll, dal):
    return ('actor "Nhân viên" as U\n'
            'participant "{}" as V\n'
            'participant "{}" as B\n'
            'participant "{}" as D\n'
            'database "CSDL" as DB\n').format(view, bll, dal)

def s_manage(entity, view, bll, dal):
    return parts_admin(view, bll, dal) + (
        'U -> V : Chọn chức năng quản lý {e}\n'
        'activate V\n'
        'V -> B : getAll()\n'
        'activate B\n'
        'B -> D : getAll()\n'
        'D -> DB : SELECT danh sách {e}\n'
        'DB --> D : dữ liệu\n'
        'D --> B : danh sách\n'
        'B --> V : danh sách\n'
        'deactivate B\n'
        'V --> U : Hiển thị danh sách {e}\n'
        'deactivate V\n').format(e=entity)

def s_add(entity, view, bll, dal):
    return parts_admin(view, bll, dal) + (
        'U -> V : Nhập thông tin {e} mới\n'
        'activate V\n'
        'V -> B : create(data)\n'
        'activate B\n'
        'B -> B : Kiểm tra tính đúng đắn dữ liệu\n'
        'alt Dữ liệu hợp lệ\n'
        '  B -> D : create(data)\n'
        '  D -> DB : INSERT {e}\n'
        '  DB --> D : mã mới\n'
        '  D --> B : mã mới\n'
        '  B --> V : thành công\n'
        '  V --> U : "Thêm {e} thành công"\n'
        'else Dữ liệu không hợp lệ\n'
        '  B --> V : lỗi\n'
        '  V --> U : "Yêu cầu nhập đúng thông tin"\n'
        'end\n'
        'deactivate B\n'
        'deactivate V\n').format(e=entity)

def s_edit(entity, view, bll, dal):
    return parts_admin(view, bll, dal) + (
        'U -> V : Chọn {e} cần sửa, nhập thông tin mới\n'
        'activate V\n'
        'V -> B : update(id, data)\n'
        'activate B\n'
        'B -> B : Kiểm tra dữ liệu\n'
        'alt Dữ liệu hợp lệ\n'
        '  B -> D : update(id, data)\n'
        '  D -> DB : UPDATE {e} SET ... WHERE id=?\n'
        '  D --> B : ok\n'
        '  B --> V : thành công\n'
        '  V --> U : "Sửa {e} thành công"\n'
        'else Không hợp lệ\n'
        '  B --> V : lỗi\n'
        '  V --> U : "Thông tin không hợp lệ"\n'
        'end\n'
        'deactivate B\n'
        'deactivate V\n').format(e=entity)

def s_delete(entity, view, bll, dal):
    return parts_admin(view, bll, dal) + (
        'U -> V : Chọn xóa {e}\n'
        'activate V\n'
        'V --> U : "Bạn có chắc chắn muốn xóa {e} này không?"\n'
        'alt Người dùng chọn Có\n'
        '  U -> V : Đồng ý\n'
        '  V -> B : delete(id)\n'
        '  activate B\n'
        '  B -> D : delete(id)\n'
        '  D -> DB : DELETE {e} WHERE id=?\n'
        '  D --> B : ok\n'
        '  B --> V : thành công\n'
        '  V --> U : Cập nhật danh sách\n'
        '  deactivate B\n'
        'else Người dùng chọn Không\n'
        '  V --> U : Đóng thông báo\n'
        'end\n'
        'deactivate V\n').format(e=entity)

def s_search(entity, view, bll, dal):
    return parts_admin(view, bll, dal) + (
        'U -> V : Nhập từ khóa tìm {e}\n'
        'activate V\n'
        'V -> B : search(keyword)\n'
        'activate B\n'
        'B -> D : search(keyword)\n'
        'D -> DB : SELECT ... WHERE ... LIKE ?\n'
        'alt Có kết quả\n'
        '  DB --> D : dữ liệu khớp\n'
        '  D --> B : kết quả\n'
        '  B --> V : kết quả\n'
        '  V --> U : Hiển thị kết quả tìm kiếm\n'
        'else Không khớp\n'
        '  DB --> D : rỗng\n'
        '  D --> B : rỗng\n'
        '  B --> V : rỗng\n'
        '  V --> U : Hiển thị bảng trắng\n'
        'end\n'
        'deactivate B\n'
        'deactivate V\n').format(e=entity)

CRUD = {'manage': s_manage, 'add': s_add, 'edit': s_edit, 'delete': s_delete, 'search': s_search}

# domain -> (view, bll, dal, entityVN)
DOM = {
 'danhmuc':   ('admin/categories.php', 'CategoryBLL', 'CategoryDAL', 'danh mục'),
 'sanpham':   ('admin/products.php', 'ProductBLL', 'ProductDAL', 'sản phẩm'),
 'khachhang': ('admin/customers.php', 'CustomerBLL', 'CustomerDAL', 'khách hàng'),
 'hoadonban': ('admin/invoices.php', 'InvoiceBLL', 'InvoiceDAL', 'hóa đơn bán'),
 'hoadonnhap':('admin/import-invoices.php', 'ImportInvoiceBLL', 'ImportInvoiceDAL', 'hóa đơn nhập'),
 'nhanvien':  ('admin/employees.php', 'EmployeeBLL', 'EmployeeDAL', 'nhân viên'),
 'taikhoan':  ('admin/accounts.php', 'AccountBLL', 'AccountDAL', 'tài khoản'),
}

# Map figure -> (filename, title, generator)
def crud_block(num, dom, ops_titles):
    view, bll, dal, ent = DOM[dom]
    for op, (suffix, vn) in ops_titles.items():
        nm = 'sq-{:02d}-{}-{}'.format(num[op], op, dom)
        seq(nm, 'Biểu đồ tuần tự - {} {}'.format(vn, ent), CRUD[op](ent, view, bll, dal))

# danh mục 40-44
crud_block({'manage':40,'add':41,'edit':42,'delete':43,'search':44}, 'danhmuc',
           {'manage':('','Quản lý'),'add':('','Thêm'),'edit':('','Sửa'),'delete':('','Xóa'),'search':('','Tìm kiếm')})

# Helper to emit a CRUD set with explicit numbers and verb labels
def crud_set(dom, nums):
    view, bll, dal, ent = DOM[dom]
    verbs = {'manage':'Quản lý','add':'Thêm','edit':'Sửa','delete':'Xóa','search':'Tìm kiếm'}
    for op, n in nums.items():
        nm = 'sq-{:02d}-{}-{}'.format(n, op, dom)
        seq(nm, 'Biểu đồ tuần tự - {} {}'.format(verbs[op], ent), CRUD[op](ent, view, bll, dal))

crud_set('danhmuc',  {'manage':40,'add':41,'edit':42,'delete':43,'search':44})
crud_set('sanpham',  {'manage':45,'add':46,'edit':47,'delete':48,'search':49})
crud_set('khachhang',{'manage':50,'add':51,'edit':52,'delete':53,'search':54})
crud_set('hoadonban',{'manage':55,'add':56,'edit':57,'delete':58,'search':59})
crud_set('hoadonnhap',{'manage':60,'add':61,'edit':62,'delete':63,'search':64})
crud_set('nhanvien', {'manage':65,'add':66,'edit':67,'delete':68,'search':69})
crud_set('taikhoan', {'manage':70,'add':71,'edit':72,'delete':73,'search':74})

# ---- Special sequences ----
seq('sq-30-dangnhap', 'Biểu đồ tuần tự - Đăng nhập',
 'actor "Người dùng" as U\nparticipant "LoginView" as V\nparticipant "AuthBLL" as B\nparticipant "AccountDAL" as D\ndatabase "CSDL" as DB\n'
 'U -> V : Nhập tên đăng nhập, mật khẩu\nactivate V\nV -> V : Kiểm tra CSRF\nV -> B : login(user, pass)\nactivate B\n'
 'B -> D : findByUsername(user)\nD -> DB : SELECT TaiKhoan WHERE TenTaiKhoan=?\nDB --> D : bản ghi\nD --> B : tài khoản\n'
 'B -> B : password_verify()\nalt Hợp lệ\n  B --> V : thành công\n  V -> V : Khởi tạo session\n  V --> U : Vào trang chủ / dashboard\n'
 'else Sai thông tin\n  B --> V : false\n  V --> U : "Sai tài khoản hoặc mật khẩu"\nend\ndeactivate B\ndeactivate V')

seq('sq-31-dangky', 'Biểu đồ tuần tự - Đăng ký tài khoản',
 'actor "Khách hàng" as U\nparticipant "RegisterView" as V\nparticipant "AuthBLL" as B\nparticipant "AccountDAL" as AD\nparticipant "CustomerDAL" as CD\ndatabase "CSDL" as DB\n'
 'U -> V : Nhập tên, email, tên ĐN, mật khẩu\nactivate V\nV -> B : register(...)\nactivate B\nB -> AD : findByUsername(user)\nAD -> DB : SELECT ...\nDB --> AD : kết quả\nAD --> B : tồn tại?\n'
 'alt Hợp lệ\n  B -> B : password_hash()\n  B -> AD : create(taiKhoan)\n  AD -> DB : INSERT TaiKhoan\n  DB --> AD : MaTaiKhoan\n  AD --> B : MaTaiKhoan\n'
 '  B -> CD : create(khachHang)\n  CD -> DB : INSERT KhachHang\n  DB --> CD : MaKhachHang\n  CD --> B : ok\n  B --> V : thành công\n  V --> U : "Tạo tài khoản thành công"\n'
 'else Không hợp lệ\n  B --> V : lỗi\n  V --> U : Yêu cầu nhập lại\nend\ndeactivate B\ndeactivate V')

seq('sq-32-doimatkhau', 'Biểu đồ tuần tự - Đổi mật khẩu',
 'actor "Khách hàng" as U\nparticipant "AccountView" as V\nparticipant "AuthBLL" as B\nparticipant "AccountDAL" as D\ndatabase "CSDL" as DB\n'
 'U -> V : Nhập mật khẩu cũ, mật khẩu mới\nactivate V\nV -> B : changePassword(id, old, new)\nactivate B\nB -> D : getById(id)\nD -> DB : SELECT MatKhau\nDB --> D : hash\nD --> B : hash\nB -> B : password_verify(old, hash)\n'
 'alt Mật khẩu cũ đúng\n  B -> B : password_hash(new)\n  B -> D : update(id, hash)\n  D -> DB : UPDATE TaiKhoan SET MatKhau=?\n  B --> V : thành công\n  V --> U : "Đổi mật khẩu thành công"\n'
 'else Sai mật khẩu cũ\n  B --> V : lỗi\n  V --> U : "Mật khẩu hiện tại không đúng"\nend\ndeactivate B\ndeactivate V')

CART = 'actor "Khách hàng" as U\nparticipant "CartView" as V\nparticipant "CartBLL" as B\nparticipant "Session" as S\n'
seq('sq-33-quanly-giohang', 'Biểu đồ tuần tự - Quản lý giỏ hàng',
 CART + 'U -> V : Xem giỏ hàng\nactivate V\nV -> B : getAll()\nactivate B\nB -> S : Đọc $_SESSION[cart]\nB -> B : getTotal()\nB --> V : danh sách + tổng tiền\ndeactivate B\nV --> U : Hiển thị giỏ hàng\ndeactivate V')
seq('sq-34-sua-giohang', 'Biểu đồ tuần tự - Sửa sản phẩm trong giỏ hàng',
 CART + 'U -> V : Thay đổi số lượng\nactivate V\nV -> B : update(id, qty)\nactivate B\nalt Hợp lệ\n  B -> S : Cập nhật số lượng\n  B -> B : getTotal()\n  B --> V : tổng tiền mới\n  V --> U : Cập nhật hiển thị\nelse Không hợp lệ\n  B --> V : lỗi\n  V --> U : "Số lượng không hợp lệ"\nend\ndeactivate B\ndeactivate V')
seq('sq-35-xoa-giohang', 'Biểu đồ tuần tự - Xóa sản phẩm trong giỏ',
 CART + 'U -> V : Chọn xóa sản phẩm\nactivate V\nV --> U : "Bạn có chắc muốn xóa?"\nU -> V : Đồng ý\nV -> B : remove(id)\nactivate B\nB -> S : unset($_SESSION[cart][id])\nB --> V : giỏ sau khi xóa\ndeactivate B\nV --> U : Cập nhật giỏ hàng\ndeactivate V')

seq('sq-36-dathang', 'Biểu đồ tuần tự - Đặt hàng / Thanh toán',
 'actor "Khách hàng" as U\nparticipant "CheckoutView" as V\nparticipant "InvoiceBLL" as B\nparticipant "InvoiceDAL" as ID\nparticipant "ProductDAL" as PD\ndatabase "CSDL" as DB\n'
 'U -> V : Nhập thông tin giao hàng + chọn TT\nactivate V\nU -> V : Nhấn "Đặt hàng"\nV -> B : checkout(maKH, info, items)\nactivate B\n'
 'alt Giỏ trống / thiếu thông tin\n  B --> V : {error}\n  V --> U : Thông báo lỗi\nelse Hợp lệ\n  B -> B : Tính tổng tiền\n  B -> ID : create(hoaDon, chiTiet)\n  ID -> DB : BEGIN; INSERT HoaDonBan + ChiTiet; COMMIT\n  DB --> ID : MaHoaDon\n  ID --> B : MaHoaDon\n'
 '  loop mỗi sản phẩm\n    B -> PD : decreaseStock(id, sl)\n    PD -> DB : UPDATE SanPham SET SoLuong-=?\n  end\n  B --> V : {success}\n  V -> V : Xóa giỏ hàng\n  V --> U : Trang "Đặt hàng thành công"\nend\ndeactivate B\ndeactivate V')

seq('sq-37-timkiem-sanpham', 'Biểu đồ tuần tự - Tìm kiếm sản phẩm',
 'actor "Khách hàng" as U\nparticipant "ProductsView" as V\nparticipant "ProductBLL" as B\nparticipant "ProductDAL" as D\ndatabase "CSDL" as DB\n'
 'U -> V : Nhập từ khóa\nactivate V\nV -> B : search(keyword)\nactivate B\nB -> D : search(keyword)\nD -> DB : SELECT ... WHERE TenSanPham LIKE ?\nDB --> D : kết quả\nD --> B : kết quả\nB --> V : danh sách\ndeactivate B\nV --> U : Hiển thị kết quả + phân trang\ndeactivate V')

seq('sq-38-xem-sanpham', 'Biểu đồ tuần tự - Xem thông tin sản phẩm',
 'actor "Khách hàng" as U\nparticipant "ProductDetailView" as V\nparticipant "ProductBLL" as PB\nparticipant "ReviewBLL" as RB\ndatabase "CSDL" as DB\n'
 'U -> V : Mở trang chi tiết sản phẩm\nactivate V\nV -> PB : getById(id)\nPB -> DB : SELECT SanPham\nDB --> PB : sản phẩm\nPB --> V : sản phẩm\nV -> RB : getByProduct(id)\nRB -> DB : SELECT DanhGia\nDB --> RB : đánh giá\nRB --> V : danh sách + điểm TB\nV -> PB : getRelated(id)\nPB --> V : sản phẩm liên quan\nV --> U : Hiển thị chi tiết + tab thông tin/đánh giá\ndeactivate V')

seq('sq-39-doc-tintuc', 'Biểu đồ tuần tự - Đọc tin tức',
 'actor "Khách hàng" as U\nparticipant "NewsView" as V\nparticipant "SettingBLL" as B\ndatabase "CSDL" as DB\n'
 'U -> V : Mở trang tin tức / bài viết\nactivate V\nV -> B : get(noi_dung)\nB -> DB : SELECT nội dung trang\nDB --> B : dữ liệu\nB --> V : nội dung\nV --> U : Hiển thị bài viết\ndeactivate V')

# 75 khóa tài khoản, 76 xóa tài khoản (báo cáo lặp), 77 thêm vào giỏ, 78 tìm kho, 79 chatbot, 80 thêm intent
seq('sq-75-khoa-taikhoan', 'Biểu đồ tuần tự - Khóa tài khoản',
 'actor "Admin" as U\nparticipant "AccountView" as V\nparticipant "AccountBLL" as B\nparticipant "AccountDAL" as D\ndatabase "CSDL" as DB\n'
 'U -> V : Chọn khóa / mở khóa tài khoản\nactivate V\nV -> B : lock(id) / unlock(id)\nactivate B\nB -> D : setStatus(id, trangThai)\nD -> DB : UPDATE TaiKhoan SET TrangThai=?\nD --> B : ok\nB --> V : thành công\nV --> U : Cập nhật trạng thái tài khoản\ndeactivate B\ndeactivate V')

seq('sq-76-xoa-taikhoan2', 'Biểu đồ tuần tự - Xóa tài khoản',
 CRUD['delete']('tài khoản', 'admin/accounts.php', 'AccountBLL', 'AccountDAL'))

seq('sq-77-them-vao-gio', 'Biểu đồ tuần tự - Thêm sản phẩm vào giỏ hàng',
 'actor "Khách hàng" as U\nparticipant "ProductView" as V\nparticipant "CartBLL" as B\nparticipant "Session" as S\n'
 'U -> V : Nhấn "Thêm vào giỏ" (id, số lượng)\nactivate V\nV -> B : add(id, qty, options)\nactivate B\nB -> S : $_SESSION[cart][id] = {qty, price}\nB -> B : getCount()\nB --> V : số lượng trong giỏ\ndeactivate B\nV --> U : Thông báo "Đã thêm vào giỏ"\ndeactivate V')

seq('sq-78-timkiem-kho', 'Biểu đồ tuần tự - Tìm kiếm sản phẩm trong kho',
 'actor "Nhân viên" as U\nparticipant "WarehouseView" as V\nparticipant "WarehouseDAL" as D\ndatabase "CSDL" as DB\n'
 'U -> V : Nhập từ khóa sản phẩm\nactivate V\nV -> D : search(keyword)\nD -> DB : SELECT KhoHang WHERE TenSanPham LIKE ?\nDB --> D : kết quả\nD --> V : danh sách tồn kho\nV --> U : Hiển thị kết quả\ndeactivate V')

seq('sq-79-chatbot', 'Biểu đồ tuần tự - Trò chuyện với AI Chatbot',
 'actor "Khách hàng" as U\nparticipant "Chatbot Widget" as W\nparticipant "chatbot.php" as API\nparticipant "ChatbotBLL" as B\nparticipant "ChatbotDAL" as CD\nparticipant "ProductBLL" as PB\ndatabase "CSDL" as DB\n'
 'U -> W : Nhập câu hỏi\nactivate W\nW -> API : POST message\nactivate API\nAPI -> B : reply(message)\nactivate B\nB -> B : Chuẩn hóa (lowercase + khử dấu)\nB -> CD : getActiveIntents()\nCD -> DB : SELECT intent WHERE KichHoat=1\nDB --> CD : intents\nCD --> B : intents\nB -> B : Khớp từ khóa\n'
 'alt Có intent khớp\n  B -> B : buildReply() + gắn sản phẩm\n  B --> API : trả lời + sản phẩm\nelse Không khớp & fallback bật\n  B -> PB : search(keywords)\n  PB --> B : sản phẩm\n  B --> API : sản phẩm liên quan\nelse Không tìm thấy\n  B --> API : câu trả lời mặc định\nend\ndeactivate B\nAPI --> W : phản hồi (text + thẻ SP)\ndeactivate API\nW --> U : Hiển thị câu trả lời\ndeactivate W')

seq('sq-80-them-intent', 'Biểu đồ tuần tự - Thêm Intent AI Chatbot (Admin)',
 'actor "Admin" as U\nparticipant "ChatbotView" as V\nparticipant "ChatbotBLL" as B\nparticipant "ChatbotDAL" as D\ndatabase "CSDL" as DB\n'
 'U -> V : Nhập tên intent, từ khóa, câu trả lời, loại gợi ý\nactivate V\nV -> B : adminCreateIntent(data)\nactivate B\nB -> B : Kiểm tra dữ liệu\n'
 'alt Hợp lệ\n  B -> D : insertIntent(data)\n  D -> DB : INSERT ChatbotIntent\n  DB --> D : MaIntent\n  D --> B : ok\n  B --> V : thành công\n  V --> U : "Đã thêm intent"\nelse Không hợp lệ\n  B --> V : lỗi\n  V --> U : "Tên/Từ khóa/Câu trả lời không được trống"\nend\ndeactivate B\ndeactivate V')

print('done, files in', SRC)
