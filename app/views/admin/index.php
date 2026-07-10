<?php /** @var array $data */ ?>
<section>
    <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Area Administrator</p>
    <h1 class="mt-1 text-3xl font-bold">Dashboard Admin</h1>
    <p class="mt-2 text-slate-600">Ringkasan platform. Detail user, toko, order, dan seller dipisah ke menu masing-masing.</p>
    <div class="mt-6 grid gap-4 md:grid-cols-5">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Total order</p><b class="mt-2 block text-3xl"><?= (int) $data['stats']['orders'] ?></b></div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">GMV</p><b class="mt-2 block text-3xl text-emerald-700">Rp<?= number_format($data['stats']['revenue'], 0, ',', '.') ?></b></div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Produk</p><b class="mt-2 block text-3xl"><?= (int) $data['stats']['products'] ?></b></div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Toko aktif</p><b class="mt-2 block text-3xl"><?= (int) $data['stats']['stores'] ?></b></div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Stok menipis</p><b class="mt-2 block text-3xl text-amber-700"><?= (int) $data['stats']['low_stock'] ?></b></div>
    </div>
</section>

<section class="mt-6 grid gap-6 lg:grid-cols-[1fr_360px]">
    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm" data-chart-url="<?= BASEURL ?>chart/adminSummary"></div>
    <aside class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-bold">Cara Membaca Grafik</h2>
        <p class="mt-2 text-sm text-slate-600">Grafik memuat data terbaru dari database setiap beberapa detik. Jika order, produk, toko, atau stok berubah, bar akan ikut berubah saat refresh otomatis.</p>
    </aside>
</section>
