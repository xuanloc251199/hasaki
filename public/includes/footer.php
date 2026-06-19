<?php
$_siteName    = setting('site_name',    'HASAKI');
$_footerAbout = setting('footer_about', 'Hệ thống mỹ phẩm chính hãng - chăm sóc sắc đẹp toàn diện cho phái nữ Việt Nam.');
$_copyright   = str_replace('{YEAR}', (string)date('Y'), setting('footer_copyright', '© ' . date('Y') . ' HASAKI. All rights reserved.'));
$_contact = [
  'address' => setting('contact_address'),
  'phone'   => setting('contact_phone'),
  'email'   => setting('contact_email'),
  'hours'   => setting('contact_hours'),
];
$_social = [
  'facebook'  => ['url' => setting('social_facebook'),  'icon' => 'fa-facebook-f'],
  'instagram' => ['url' => setting('social_instagram'), 'icon' => 'fa-instagram'],
  'tiktok'    => ['url' => setting('social_tiktok'),    'icon' => 'fa-tiktok'],
  'youtube'   => ['url' => setting('social_youtube'),   'icon' => 'fa-youtube'],
];
$_supportMenu = menu('footer_support');
$_accountMenu = menu('footer_account');
?>
  <!-- Brand strip (infinite marquee) -->
  <section class="bg-white border-t border-b border-gray-100 py-8 overflow-hidden">
    <?php
      $brands = ['CHANEL', 'DIOR', "L'ORÉAL", 'MAYBELLINE', 'BIODERMA', 'LANCÔME', 'MAC', 'YSL', 'CLINIQUE', 'ESTÉE LAUDER', 'NARS', 'BOBBI BROWN'];
    ?>
    <div class="relative">
      <div class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
      <div class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>
      <div class="flex w-max animate-marquee gap-16 text-gray-400 text-2xl lg:text-3xl font-display font-bold">
        <?php foreach ($brands as $b): ?>
          <span class="hover:text-brand-500 transition-colors cursor-default whitespace-nowrap"><?= e($b) ?></span>
        <?php endforeach; ?>
        <?php foreach ($brands as $b): ?>
          <span class="hover:text-brand-500 transition-colors cursor-default whitespace-nowrap" aria-hidden="true"><?= e($b) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Main footer -->
  <footer class="bg-gradient-to-br from-gray-900 to-slate-900 text-gray-300 pt-16 pb-6 mt-0">
    <div class="max-w-7xl mx-auto px-4 lg:px-6">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-10 mb-10">

        <!-- Brand -->
        <div class="col-span-2 lg:col-span-1">
          <div class="flex items-center gap-2 mb-4">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-400 flex items-center justify-center text-white">
              <i class="fa-solid fa-spa"></i>
            </div>
            <div class="font-display text-2xl font-extrabold text-white"><?= e($_siteName) ?></div>
          </div>
          <p class="text-sm leading-relaxed text-gray-400 mb-5"><?= e($_footerAbout) ?></p>
          <div class="flex gap-2">
            <?php foreach ($_social as $s): if (empty($s['url'])) continue; ?>
              <a href="<?= e($s['url']) ?>" target="_blank" rel="noopener"
                 class="w-9 h-9 rounded-full bg-white/10 hover:bg-brand-500 flex items-center justify-center text-white transition-colors">
                <i class="fa-brands <?= e($s['icon']) ?> text-sm"></i>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Support menu -->
        <div>
          <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Hỗ trợ</h4>
          <ul class="space-y-2.5 text-sm">
            <?php foreach ($_supportMenu as $m):
              $href = strpos($m['DuongDan'], '://') !== false ? $m['DuongDan'] : url($m['DuongDan']);
            ?>
              <li>
                <a href="<?= e($href) ?>" <?= $m['MoCuaSoMoi'] ? 'target="_blank" rel="noopener"' : '' ?>
                   class="hover:text-brand-300 transition-colors">
                  <?php if ($m['Icon']): ?><i class="fa-solid <?= e($m['Icon']) ?> mr-1"></i><?php endif; ?>
                  <?= e($m['TieuDe']) ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Account menu -->
        <div>
          <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Tài khoản</h4>
          <ul class="space-y-2.5 text-sm">
            <?php foreach ($_accountMenu as $m):
              $href = strpos($m['DuongDan'], '://') !== false ? $m['DuongDan'] : url($m['DuongDan']);
            ?>
              <li>
                <a href="<?= e($href) ?>" <?= $m['MoCuaSoMoi'] ? 'target="_blank" rel="noopener"' : '' ?>
                   class="hover:text-brand-300 transition-colors">
                  <?= e($m['TieuDe']) ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Contact -->
        <div class="col-span-2 lg:col-span-1">
          <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Liên hệ</h4>
          <ul class="space-y-3 text-sm">
            <?php if ($_contact['address']): ?>
              <li class="flex items-start gap-3">
                <i class="fa-solid fa-location-dot text-brand-400 mt-0.5 w-4 shrink-0"></i>
                <span><?= e($_contact['address']) ?></span>
              </li>
            <?php endif; ?>
            <?php if ($_contact['phone']): ?>
              <li class="flex items-start gap-3">
                <i class="fa-solid fa-phone text-brand-400 mt-0.5 w-4 shrink-0"></i>
                <a href="tel:<?= e(preg_replace('/\s+/', '', $_contact['phone'])) ?>" class="hover:text-brand-300"><?= e($_contact['phone']) ?></a>
              </li>
            <?php endif; ?>
            <?php if ($_contact['email']): ?>
              <li class="flex items-start gap-3">
                <i class="fa-solid fa-envelope text-brand-400 mt-0.5 w-4 shrink-0"></i>
                <a href="mailto:<?= e($_contact['email']) ?>" class="hover:text-brand-300"><?= e($_contact['email']) ?></a>
              </li>
            <?php endif; ?>
            <?php if ($_contact['hours']): ?>
              <li class="flex items-start gap-3">
                <i class="fa-solid fa-clock text-brand-400 mt-0.5 w-4 shrink-0"></i>
                <span><?= e($_contact['hours']) ?></span>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>

      <!-- Payment methods -->
      <div class="border-t border-white/10 pt-6 flex flex-wrap items-center justify-between gap-4">
        <div class="text-xs text-gray-500"><?= $_copyright // allow {YEAR} replaced ?></div>
        <div class="flex items-center gap-2 text-xs text-gray-500">
          <span class="mr-2">Thanh toán:</span>
          <span class="px-2.5 py-1 bg-white/5 rounded-md font-bold text-white text-[10px]">VISA</span>
          <span class="px-2.5 py-1 bg-white/5 rounded-md font-bold text-white text-[10px]">MASTER</span>
          <span class="px-2.5 py-1 bg-white/5 rounded-md font-bold text-white text-[10px]">VNPAY</span>
          <span class="px-2.5 py-1 bg-white/5 rounded-md font-bold text-white text-[10px]">MOMO</span>
          <span class="px-2.5 py-1 bg-white/5 rounded-md font-bold text-white text-[10px]">COD</span>
        </div>
      </div>
    </div>
  </footer>

  <script>window.HASAKI_CHATBOT_URL = '<?= url('chatbot.php') ?>';</script>
  <script src="<?= asset('js/main.js') ?>"></script>
  <script src="<?= asset('js/chatbot.js') ?>"></script>
</body>
</html>
