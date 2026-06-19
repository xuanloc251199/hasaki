<?php
require_once __DIR__ . '/../config/config.php';
$page_title = 'Về Hasaki - ' . SITE_NAME;
require __DIR__ . '/includes/header.php';
?>
<section class="page-head">
  <div class="head-inner">
    <h1>Về Hasaki</h1>
    <div class="breadcrumbs"><a href="<?= url('index.php') ?>">Trang chủ</a> / Về chúng tôi</div>
  </div>
</section>

<div class="container" style="padding:32px 16px;max-width:900px">
  <div style="background:#fff;border-radius:14px;padding:48px;box-shadow:0 4px 14px rgba(0,0,0,.06)">
    <h2>Câu chuyện thương hiệu</h2>
    <p>Hasaki được thành lập với sứ mệnh mang đến cho người tiêu dùng Việt Nam những sản phẩm mỹ phẩm chính hãng từ những thương hiệu hàng đầu thế giới với mức giá hợp lý nhất.</p>
    <p>Trải qua nhiều năm phát triển, Hasaki đã trở thành điểm đến tin cậy của hàng triệu khách hàng yêu thích làm đẹp với hệ thống cửa hàng và website thương mại điện tử phủ sóng toàn quốc.</p>
    <h2 class="mt-3">Tầm nhìn &amp; Sứ mệnh</h2>
    <p><strong>Tầm nhìn:</strong> Trở thành nhà bán lẻ mỹ phẩm hàng đầu Việt Nam, mang trải nghiệm mua sắm đẳng cấp đến mọi khách hàng.</p>
    <p><strong>Sứ mệnh:</strong> Cung cấp sản phẩm 100% chính hãng - dịch vụ tư vấn chuyên nghiệp - giá tốt nhất thị trường.</p>
    <h2 class="mt-3">Cam kết</h2>
    <ul>
      <li>100% sản phẩm chính hãng, có nguồn gốc rõ ràng</li>
      <li>Giao hàng nhanh trong 30 phút khu vực nội thành</li>
      <li>Đổi trả miễn phí trong vòng 7 ngày</li>
      <li>Đội ngũ tư vấn da liễu chuyên nghiệp 24/7</li>
    </ul>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
