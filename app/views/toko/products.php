<?php
/** @var array $data */
$maxCategory = max(array_map(fn ($item) => (int) $item['total'], $data['categories'] ?: [['total' => 1]]));
?>
<section class="mb-6">
    <h1 class="text-3xl font-bold">Manajemen Produk</h1>
    <p class="mt-2 text-slate-600">Tambah produk, edit produk, hapus/nonaktifkan, kelola stok, kategori, dan foto produk.</p>
</section>

<section class="grid gap-6 xl:grid-cols-[360px_1fr]">
    <aside class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="font-bold">Tambah Produk Baru</h2>
        <form method="post" action="<?= BASEURL ?>toko/product" class="mt-4 grid gap-3">
            <input name="name" required placeholder="Nama produk" class="rounded-md border border-slate-300 px-3 py-2">
            <input name="category" required placeholder="Kategori" class="rounded-md border border-slate-300 px-3 py-2">
            <input name="price" required type="number" min="0" placeholder="Harga" class="rounded-md border border-slate-300 px-3 py-2">
            <input name="stock" required type="number" min="0" placeholder="Stok" class="rounded-md border border-slate-300 px-3 py-2">
            <input name="image_url" placeholder="URL foto produk" class="rounded-md border border-slate-300 px-3 py-2">
            <textarea name="description" placeholder="Deskripsi" class="rounded-md border border-slate-300 px-3 py-2"></textarea>
            <button class="rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white">Simpan produk</button>
        </form>
        <div class="mt-5 flex flex-wrap gap-2">
            <?php foreach ($data['categories'] as $category): ?>
                <span class="rounded-md bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-800"><?= htmlspecialchars($category['category']) ?> (<?= (int) $category['total'] ?>)</span>
            <?php endforeach; ?>
        </div>
        <div class="mt-6 rounded-md bg-slate-100 p-4">
            <h3 class="font-semibold">Grafik Kategori</h3>
            <?php foreach ($data['categories'] as $category): ?>
                <?php $percent = max(10, ((int) $category['total'] / $maxCategory) * 100); ?>
                <div class="mt-3">
                    <div class="mb-1 flex justify-between text-xs"><span><?= htmlspecialchars($category['category']) ?></span><b><?= (int) $category['total'] ?></b></div>
                    <div class="h-2 rounded-full bg-white"><div class="h-2 rounded-full bg-emerald-600" style="width: <?= $percent ?>%"></div></div>
                </div>
            <?php endforeach; ?>
            <?php if (!$data['categories']): ?><p class="mt-3 text-sm text-slate-500">Belum ada kategori.</p><?php endif; ?>
        </div>
    </aside>

    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center justify-between"><h2 class="text-xl font-bold">Daftar Produk</h2><span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600"><?= count($data['products']) ?> produk</span></div>
        <?php if (!$data['products']): ?>
            <div class="rounded-md border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">Belum ada produk.</div>
        <?php else: ?>
            <div class="overflow-x-auto rounded-md border border-slate-200">
                <table class="w-full text-sm">
                    <thead class="bg-slate-100 text-slate-600"><tr><th class="p-3 text-left">Produk</th><th class="p-3">Kategori</th><th class="p-3 text-right">Harga</th><th class="p-3">Stok</th><th class="p-3">Status</th><th class="p-3">Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($data['products'] as $product): ?>
                        <tr class="border-t align-top">
                            <form method="post" action="<?= BASEURL ?>toko/updateProduct/<?= $product['id'] ?>">
                                <td class="p-3"><input name="name" value="<?= htmlspecialchars($product['name']) ?>" class="w-full rounded border border-slate-300 px-2 py-2"><input type="hidden" name="image_url" value="<?= htmlspecialchars($product['image_url']) ?>"><input type="hidden" name="description" value="<?= htmlspecialchars($product['description']) ?>"></td>
                                <td class="p-3"><input name="category" value="<?= htmlspecialchars($product['category']) ?>" class="w-32 rounded border border-slate-300 px-2 py-2"></td>
                                <td class="p-3"><input name="price" type="number" value="<?= (int) $product['price'] ?>" class="w-28 rounded border border-slate-300 px-2 py-2 text-right"></td>
                                <td class="p-3"><input name="stock" type="number" value="<?= (int) $product['stock'] ?>" class="w-20 rounded border border-slate-300 px-2 py-2"></td>
                                <td class="p-3"><select name="status" class="rounded border border-slate-300 px-2 py-2"><option <?= $product['status'] === 'active' ? 'selected' : '' ?>>active</option><option <?= $product['status'] === 'inactive' ? 'selected' : '' ?>>inactive</option></select></td>
                                <td class="p-3"><div class="flex gap-2"><button class="rounded bg-slate-900 px-3 py-2 text-xs font-semibold text-white">Update</button><a class="rounded bg-red-600 px-3 py-2 text-xs font-semibold text-white" href="<?= BASEURL ?>toko/deleteProduct/<?= $product['id'] ?>">Hapus</a></div></td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</section>

<section class="mt-6 rounded-lg border border-slate-200 bg-white p-5 shadow-sm" data-chart-url="<?= BASEURL ?>chart/sellerProducts"></section>
