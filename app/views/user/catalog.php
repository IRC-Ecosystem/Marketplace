<?php /** @var array $data */ ?>
<section class="mb-6">
    <h1 class="text-3xl font-bold">Katalog Produk</h1>
    <p class="mt-2 text-slate-600">Browse produk UMKM dan tambahkan ke keranjang.</p>
</section>
<section>
    <form class="mb-4 flex gap-2">
        <input name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Cari produk..." class="rounded-md border border-slate-300 px-3 py-2">
        <button class="rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">Cari</button>
    </form>
    <?php if (!$data['products']): ?>
        <div class="rounded-lg border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">Produk belum tersedia.</div>
    <?php else: ?>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <?php foreach ($data['products'] as $product): ?>
                <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="aspect-[4/3] bg-slate-100"><?php if ($product['image_url']): ?><img src="<?= htmlspecialchars($product['image_url']) ?>" class="h-full w-full object-cover" alt="<?= htmlspecialchars($product['name']) ?>"><?php endif; ?></div>
                    <div class="p-4">
                        <div class="flex items-center justify-between gap-3"><p class="text-xs text-slate-500"><?= htmlspecialchars($product['store_name']) ?></p><span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">Stok <?= (int) $product['stock'] ?></span></div>
                        <h3 class="mt-2 font-semibold"><?= htmlspecialchars($product['name']) ?></h3><p class="text-sm text-slate-600"><?= htmlspecialchars($product['category']) ?></p><p class="mt-2 font-bold text-emerald-700">Rp<?= number_format($product['price'], 0, ',', '.') ?></p>
                        <form method="post" action="<?= BASEURL ?>user/addCart" class="mt-3 flex gap-2"><input type="hidden" name="product_id" value="<?= $product['id'] ?>"><input type="number" name="qty" min="1" max="<?= (int) $product['stock'] ?>" value="1" class="w-20 rounded-md border border-slate-300 px-2 py-2"><button class="flex-1 rounded-md bg-slate-900 px-3 py-2 text-sm font-semibold text-white">Tambah</button></form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
