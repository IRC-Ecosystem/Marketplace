<?php /** @var array $data */ ?>
<section class="mb-6">
    <h1 class="text-3xl font-bold">Stok & Rekomendasi Restock</h1>
    <p class="mt-2 text-slate-600">Stok menipis, produk habis/nonaktif, rekomendasi restock, dan tombol integrasi SupplierHub.</p>
</section>
<section class="mb-6 rounded-lg border border-slate-200 bg-white p-5 shadow-sm" data-chart-url="<?= BASEURL ?>chart/sellerRestock"></section>
<section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
    <?php if (!$data['lowStock']): ?><div class="rounded-md border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">Tidak ada stok menipis.</div><?php endif; ?>
    <?php foreach ($data['lowStock'] as $product): ?>
        <div class="mb-3 flex flex-wrap items-center justify-between gap-3 rounded-md border border-amber-200 bg-amber-50 p-4">
            <div><b><?= htmlspecialchars($product['name']) ?></b><p class="text-sm text-amber-800">Sisa stok <?= (int) $product['stock'] ?>. Rekomendasi restock via SupplierHub.</p></div>
            <a href="#" class="rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white">Restock ke SupplierHub</a>
        </div>
    <?php endforeach; ?>
</section>
