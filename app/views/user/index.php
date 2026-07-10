<?php /** @var array $data */ ?>
<section class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Area Pembeli</p>
    <h1 class="mt-1 text-3xl font-bold">Dashboard Pembeli</h1>
    <p class="mt-2 text-slate-600">Ringkasan akun pembeli. Katalog dan order dipisah ke menu masing-masing.</p>
</section>

<section class="grid gap-4 md:grid-cols-3">
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Saldo SmartBank lokal</p><p class="mt-2 text-3xl font-bold text-emerald-700">Rp<?= number_format($data['user']['balance'], 0, ',', '.') ?></p></div>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Total order</p><p class="mt-2 text-3xl font-bold"><?= count($data['orders']) ?></p></div>
    <a href="<?= BASEURL ?>user/cart" class="rounded-xl border border-slate-200 bg-slate-900 p-5 text-white shadow-sm"><p class="text-sm text-slate-300">Aksi cepat</p><p class="mt-2 text-3xl font-bold">Keranjang</p></a>
</section>

<section class="mt-8 grid gap-6 lg:grid-cols-[1fr_360px]">
    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-bold">Produk Unggulan</h2>
        <div class="mt-4 grid gap-3 md:grid-cols-2">
            <?php foreach ($data['featured'] as $product): ?>
                <div class="rounded-md border border-slate-200 p-4"><p class="text-xs text-slate-500"><?= htmlspecialchars($product['store_name']) ?></p><h3 class="mt-1 font-semibold"><?= htmlspecialchars($product['name']) ?></h3><p class="mt-2 font-bold text-emerald-700">Rp<?= number_format($product['price'], 0, ',', '.') ?></p></div>
            <?php endforeach; ?>
        </div>
    </div>
    <aside class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-bold">Voucher Pembeli</h2>
        <div class="mt-4 grid gap-3">
            <?php foreach ($data['vouchers'] as $voucher): ?>
                <div class="rounded-md border border-emerald-200 bg-emerald-50 p-3"><b class="text-emerald-800"><?= htmlspecialchars($voucher['code']) ?></b><p class="text-sm text-emerald-700"><?= htmlspecialchars($voucher['label']) ?></p></div>
            <?php endforeach; ?>
        </div>
    </aside>
</section>
