<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="grid gap-8 p-6 md:grid-cols-[1.1fr_0.9fr] md:items-center lg:p-10">
        <div>
            <p class="mb-3 text-sm font-semibold uppercase tracking-wide text-emerald-700">Marketplace UMKM RPL</p>
            <h1 class="max-w-3xl text-4xl font-bold leading-tight md:text-6xl">PasarKita menghubungkan pembeli, seller, dan alur pembayaran SmartBank.</h1>
            <p class="mt-5 max-w-2xl text-lg text-slate-600">Katalog produk, checkout, dashboard seller, restock SupplierHub, dan ledger transaksi dibuat agar alur ekosistem UMKM terlihat jelas saat demo.</p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a href="<?= BASEURL ?>auth/register" class="rounded-md bg-emerald-700 px-5 py-3 font-medium text-white shadow-sm">Mulai belanja</a>
                <a href="<?= BASEURL ?>auth/login" class="rounded-md border border-slate-300 bg-white px-5 py-3 font-medium">Masuk dashboard</a>
            </div>
        </div>
        <div class="grid gap-3">
            <div class="rounded-xl bg-slate-950 p-5 text-white">
                <p class="text-sm text-slate-300">Alur transaksi</p>
                <p class="mt-2 text-2xl font-bold">Cart -> Fee -> Payment Request -> Ledger</p>
                <div class="mt-5 grid grid-cols-3 gap-2 text-center text-xs">
                    <span class="rounded-md bg-white/10 p-2">Marketplace</span>
                    <span class="rounded-md bg-white/10 p-2">Gateway</span>
                    <span class="rounded-md bg-white/10 p-2">SmartBank</span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl bg-emerald-50 p-4"><b class="text-2xl text-emerald-800">2%</b><br><span class="text-sm text-emerald-700">Fee marketplace</span></div>
                <div class="rounded-xl bg-amber-50 p-4"><b class="text-2xl text-amber-800">Restock</b><br><span class="text-sm text-amber-700">SupplierHub ready</span></div>
            </div>
        </div>
    </div>
</section>

<section class="mt-10 grid gap-4 md:grid-cols-3">
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><b>Dashboard Pembeli</b><p class="mt-2 text-sm text-slate-600">Katalog, voucher, keranjang, checkout, dan riwayat order.</p></div>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><b>Dashboard Seller</b><p class="mt-2 text-sm text-slate-600">Produk, pesanan, promosi, chat, keuangan, restock, dan performa toko.</p></div>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><b>Dashboard Admin</b><p class="mt-2 text-sm text-slate-600">Monitoring user, toko, order, stok menipis, dan fitur seller.</p></div>
</section>

<section class="mt-12">
    <div class="mb-5 flex items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Produk terbaru</h2>
            <p class="text-slate-600">Katalog aktif dari seller UMKM.</p>
        </div>
        <a href="<?= BASEURL ?>auth/login" class="text-sm font-semibold text-emerald-700">Masuk untuk checkout</a>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <?php foreach (array_slice($data['products'], 0, 8) as $product): ?>
            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="aspect-[4/3] bg-slate-100">
                    <?php if ($product['image_url']): ?>
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" class="h-full w-full object-cover" alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php endif; ?>
                </div>
                <div class="p-4">
                    <p class="text-xs text-slate-500"><?= htmlspecialchars($product['store_name']) ?></p>
                    <h3 class="mt-1 font-semibold"><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="mt-2 font-bold text-emerald-700">Rp<?= number_format($product['price'], 0, ',', '.') ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
