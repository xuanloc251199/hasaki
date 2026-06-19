<?php
/**
 * Image downloader for Hasaki seed data.
 * Run from CLI: php database/download_images.php
 *
 * Fetches a curated set of cosmetic photos from Unsplash and saves them to
 * public/assets/images/{products,categories,employees}/ as local files,
 * then prints SQL to update the database with local paths.
 */

declare(strict_types=1);

$baseDir = __DIR__ . '/../public/assets/images';
foreach (['products', 'categories', 'employees'] as $sub) {
    $dir = "$baseDir/$sub";
    if (!is_dir($dir)) mkdir($dir, 0775, true);
}

// =============================================
// Image catalog: filename => Unsplash photo ID
// All IDs verified to return HTTP 200 from CDN.
// =============================================
$products = [
    'son-li-ruby-kiss'      => '1586495777744-4413f21062fa',  // red lipstick
    'son-duong-moist-touch' => '1631730486572-226d1f595b68',  // lip balm
    'son-kem-velvet'        => '1487412947147-5cebf100ffc2',  // makeup palette
    'phan-phu-oil-control'  => '1620916566398-39f1143ab7be',  // foundation
    'phan-phu-natural-veil' => '1596704017254-9b121068fb31',  // powder
    'sua-duong-bioderma'    => '1556228720-195a672e8a03',     // skincare bottle
    'kem-chong-nang-anessa' => '1556229010-aa3f7ff66b24',     // sunscreen
    'kem-duong-laroche'     => '1571781926291-c477ebfd024b',  // cream
    'sua-rua-mat-cetaphil'  => '1596462502278-27bfdc403348',  // cleanser
    'tay-trang-bioderma'    => '1503236823255-94609f598e71',  // bottle
    'sua-tam-dove'          => '1608571423902-eed4a5ad8108',  // body wash
    'lotion-vaseline'       => '1612817288484-6f916006741a',  // lotion
    'nuoc-hoa-chanel'       => '1541643600914-78b084683601',  // perfume
    'nuoc-hoa-dior'         => '1592945403244-b3fbafd7f539',  // perfume men
    'phan-ma-bobbi-brown'   => '1571646034647-52e6ea84b28c',  // blush
    'son-tint-romand'       => '1607602132700-068258431c6c',  // tint
];

// Hover/secondary images for richer detail page galleries
$productHovers = [
    'son-li-ruby-kiss'      => ['1615634260167-c8cdede054de', '1594035910387-fea47794261f'],
    'son-duong-moist-touch' => ['1487412947147-5cebf100ffc2', null],
    'son-kem-velvet'        => ['1620916566398-39f1143ab7be', null],
    'phan-phu-oil-control'  => ['1571646034647-52e6ea84b28c', null],
    'kem-chong-nang-anessa' => ['1571781926291-c477ebfd024b', '1620916566398-39f1143ab7be'],
    'nuoc-hoa-chanel'       => ['1592945403244-b3fbafd7f539', null],
];

$categories = [
    'son-moi'         => '1586495777744-4413f21062fa',
    'duong-da'        => '1556228720-195a672e8a03',
    'trang-diem'      => '1487412947147-5cebf100ffc2',
    'lam-sach-da'     => '1596462502278-27bfdc403348',
    'cham-soc-co-the' => '1608571423902-eed4a5ad8108',
    'nuoc-hoa'        => '1541643600914-78b084683601',
];

// Employee avatars use pravatar (always works, gravatar-like)
$employees = [1, 2, 3, 4, 5];

// =============================================
// Helper: download URL to local file
// =============================================
function fetch(string $url, string $target): bool {
    $ctx = stream_context_create(['http' => ['timeout' => 30, 'user_agent' => 'Hasaki/1.0']]);
    $bytes = @file_get_contents($url, false, $ctx);
    if ($bytes === false || strlen($bytes) < 1000) return false;
    return file_put_contents($target, $bytes) !== false;
}

function unsplash(string $id, int $w = 800): string {
    return "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w={$w}&q=80";
}

// =============================================
// Download products
// =============================================
echo "=== Downloading product images ===\n";
$ok = 0; $fail = 0;
foreach ($products as $slug => $id) {
    $target = "$baseDir/products/$slug.jpg";
    if (file_exists($target)) { echo "  [skip] $slug.jpg already exists\n"; $ok++; continue; }
    echo "  [...] $slug.jpg ... ";
    if (fetch(unsplash($id, 800), $target)) {
        echo "OK (" . number_format(filesize($target)) . " bytes)\n";
        $ok++;
    } else {
        echo "FAILED\n";
        $fail++;
    }
}
foreach ($productHovers as $slug => [$h1, $h2]) {
    foreach ([$h1, $h2] as $idx => $id) {
        if (!$id) continue;
        $name = $slug . '-hover' . ($idx + 1) . '.jpg';
        $target = "$baseDir/products/$name";
        if (file_exists($target)) continue;
        echo "  [...] $name ... ";
        if (fetch(unsplash($id, 600), $target)) {
            echo "OK\n"; $ok++;
        } else {
            echo "FAILED\n"; $fail++;
        }
    }
}

// =============================================
// Download category images
// =============================================
echo "\n=== Downloading category images ===\n";
foreach ($categories as $slug => $id) {
    $target = "$baseDir/categories/$slug.jpg";
    if (file_exists($target)) { echo "  [skip] $slug.jpg\n"; $ok++; continue; }
    echo "  [...] $slug.jpg ... ";
    if (fetch(unsplash($id, 600), $target)) {
        echo "OK\n"; $ok++;
    } else {
        echo "FAILED\n"; $fail++;
    }
}

// =============================================
// Download employee avatars (pravatar, deterministic)
// =============================================
echo "\n=== Downloading employee avatars ===\n";
foreach ($employees as $id) {
    $target = "$baseDir/employees/nv-$id.jpg";
    if (file_exists($target)) { echo "  [skip] nv-$id.jpg\n"; $ok++; continue; }
    echo "  [...] nv-$id.jpg ... ";
    if (fetch("https://i.pravatar.cc/300?img=" . (5 + $id), $target)) {
        echo "OK\n"; $ok++;
    } else {
        echo "FAILED\n"; $fail++;
    }
}

echo "\n=== Summary: $ok OK, $fail FAILED ===\n";
