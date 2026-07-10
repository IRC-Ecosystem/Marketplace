<?php
/** @var array $data */
$summary = $data['summary'];
?>
<section class="grid gap-6 xl:grid-cols-[1fr_360px]">
    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="border-b p-4">
            <h1 class="text-2xl font-bold">Keranjang</h1>
            <p class="mt-1 text-sm text-slate-500">Produk yang siap dihitung sebagai checkout marketplace.</p>
        </div>

        <?php foreach ($summary['items'] as $item): $product = $item['product']; ?>
            <div class="flex items-center justify-between gap-4 border-b p-4">
                <div>
                    <h2 class="font-semibold"><?= htmlspecialchars($product['name']) ?></h2>
                    <p class="text-sm text-slate-600"><?= (int) $item['qty'] ?> x Rp<?= number_format($product['price'], 0, ',', '.') ?></p>
                </div>
                <div class="text-right">
                    <p class="font-bold">Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></p>
                    <a href="<?= BASEURL ?>user/removeCart/<?= $product['id'] ?>" class="text-sm font-semibold text-red-600">Hapus</a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (!$summary['items']): ?>
            <div class="p-8 text-center">
                <p class="font-semibold">Keranjang masih kosong</p>
                <p class="mt-1 text-sm text-slate-500">Pilih produk dari dashboard pembeli untuk mulai checkout.</p>
                <a href="<?= BASEURL ?>user" class="mt-4 inline-block rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white">Lihat produk</a>
            </div>
        <?php endif; ?>
    </div>

    <aside class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-bold">Ringkasan Fee</h2>
        <?php foreach (['subtotal' => 'Subtotal', 'marketplaceFee' => 'Fee marketplace 2%', 'gatewayFee' => 'Fee gateway 0.5%', 'bankFee' => 'Fee bank 1%', 'tax' => 'Pajak 2%', 'shipping' => 'Logistik'] as $key => $label): ?>
            <div class="mt-3 flex justify-between text-sm"><span><?= $label ?></span><b>Rp<?= number_format($summary[$key], 0, ',', '.') ?></b></div>
        <?php endforeach; ?>
        <div class="mt-4 flex justify-between border-t pt-4 text-lg font-bold"><span>Total</span><span>Rp<?= number_format($summary['total'], 0, ',', '.') ?></span></div>
        <?php if ($summary['items']): ?>
            <a href="<?= BASEURL ?>user/checkout" class="mt-5 block rounded-md bg-emerald-700 px-4 py-3 text-center font-semibold text-white">Lanjut checkout</a>
        <?php else: ?>
            <button disabled class="mt-5 block w-full rounded-md bg-slate-200 px-4 py-3 text-center font-semibold text-slate-500">Keranjang kosong</button>
        <?php endif; ?>
    </aside>
</section>
