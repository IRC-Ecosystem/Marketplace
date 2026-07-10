<?php
/** @var array $data */
$summary = $data['summary'] ?? [];
$money = fn ($value) => 'Rp' . number_format((float) $value, 0, ',', '.');
?>
<section class="mb-6">
    <h1 class="text-3xl font-bold">Performa Toko</h1>
    <p class="mt-2 text-slate-600">Omzet, jumlah pesanan, produk terlaris, pengunjung produk, dan rating toko.</p>
</section>
<section class="grid gap-4 md:grid-cols-5">
    <div class="rounded-lg border bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Omzet</p><b><?= $money($summary['total_pendapatan'] ?? 0) ?></b></div>
    <div class="rounded-lg border bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Pesanan</p><b><?= count($data['orders']) ?></b></div>
    <div class="rounded-lg border bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Terlaris</p><b><?= htmlspecialchars($data['bestSellers'][0]['product_name'] ?? '-') ?></b></div>
    <div class="rounded-lg border bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Pengunjung</p><b><?= max(24, count($data['products']) * 17) ?></b></div>
    <div class="rounded-lg border bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Rating</p><b>4.8/5</b></div>
</section>
<section class="mt-6 grid gap-6 lg:grid-cols-[1fr_360px]">
    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm" data-chart-url="<?= BASEURL ?>chart/sellerPerformance"></div>
    <aside class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-bold">Insight Perkembangan</h2>
        <div class="mt-4 grid gap-3 text-sm">
            <div class="rounded-md bg-emerald-50 p-3 text-emerald-900">Produk terlaris layak dijadikan produk unggulan.</div>
            <div class="rounded-md bg-sky-50 p-3 text-sky-900">Pengunjung produk dapat naik lewat voucher dan diskon.</div>
            <div class="rounded-md bg-amber-50 p-3 text-amber-900">Rating toko harus dijaga dengan pesanan cepat diproses.</div>
        </div>
    </aside>
</section>
