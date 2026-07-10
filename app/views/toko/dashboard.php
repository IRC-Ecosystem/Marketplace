<?php
/** @var array $data */
$summary = $data['summary'] ?? [];
$money = fn ($value) => 'Rp' . number_format((float) $value, 0, ',', '.');
$totalProducts = max(count($data['products']), 1);
$activeProducts = count(array_filter($data['products'], fn ($product) => $product['status'] === 'active'));
?>
<section class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Area Seller</p>
    <h1 class="mt-1 text-3xl font-bold">Dashboard Seller</h1>
    <p class="mt-2 text-slate-600">Ringkasan cepat performa toko. Grafik di bawah mengambil data terbaru dari database secara otomatis.</p>
</section>

<section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Omzet hari ini</p><b class="mt-2 block text-2xl text-emerald-700"><?= $money($summary['omzet_hari_ini'] ?? 0) ?></b></div>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Omzet bulan ini</p><b class="mt-2 block text-2xl"><?= $money($summary['omzet_bulan_ini'] ?? 0) ?></b></div>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Pesanan masuk</p><b class="mt-2 block text-2xl"><?= (int) ($summary['pesanan_baru'] ?? 0) ?></b></div>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Stok hampir habis</p><b class="mt-2 block text-2xl text-amber-700"><?= count($data['lowStock']) ?></b></div>
</section>

<section class="mt-6 grid gap-6 xl:grid-cols-[1.25fr_0.75fr]">
    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm" data-chart-url="<?= BASEURL ?>chart/sellerDashboard"></div>
    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-bold">Kesehatan Katalog</h2>
        <div class="mt-4 grid gap-3">
            <div class="rounded-md bg-slate-100 p-4">
                <div class="flex justify-between text-sm"><span>Produk aktif</span><b><?= $activeProducts ?>/<?= $totalProducts ?></b></div>
                <div class="mt-2 h-2 rounded-full bg-white"><div class="h-2 rounded-full bg-emerald-600" style="width: <?= ($activeProducts / $totalProducts) * 100 ?>%"></div></div>
            </div>
            <div class="rounded-md bg-amber-50 p-4">
                <div class="flex justify-between text-sm text-amber-900"><span>Risiko stok</span><b><?= count($data['lowStock']) ?> produk</b></div>
                <p class="mt-1 text-xs text-amber-800">Gunakan halaman Restock untuk prioritas SupplierHub.</p>
            </div>
        </div>
    </div>
</section>

<section class="mt-6 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
    <h2 class="text-xl font-bold">Rekomendasi Pengembangan Toko</h2>
    <div class="mt-4 grid gap-3 md:grid-cols-3">
        <div class="rounded-md bg-emerald-50 p-4"><b class="text-emerald-900">Dorong produk terlaris</b><p class="mt-1 text-sm text-emerald-800">Jadikan produk terlaris sebagai produk unggulan atau bundling.</p></div>
        <div class="rounded-md bg-amber-50 p-4"><b class="text-amber-900">Amankan stok</b><p class="mt-1 text-sm text-amber-800">Produk dengan stok <= 5 perlu restock agar pesanan tidak tertahan.</p></div>
        <div class="rounded-md bg-sky-50 p-4"><b class="text-sky-900">Pantau order aktif</b><p class="mt-1 text-sm text-sky-800">Pesanan masuk dan diproses perlu ditangani lebih dulu.</p></div>
    </div>
</section>
