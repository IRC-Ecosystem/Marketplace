<?php
/** @var array $data */
$summary = $data['summary'];
?>
<section class="mx-auto max-w-2xl rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
    <h1 class="text-2xl font-bold">Checkout</h1>
    <p class="mt-2 text-slate-600">Total pembayaran akan dicatat sebagai payment request PasarKita ke SmartBank lokal.</p>

    <?php if (!$summary['items']): ?>
        <div class="mt-5 rounded-md border border-dashed border-slate-300 p-6 text-center">
            <p class="font-semibold">Belum ada produk untuk checkout</p>
            <p class="mt-1 text-sm text-slate-500">Keranjang kosong, jadi belum ada payment request yang bisa dibuat.</p>
            <a href="<?= BASEURL ?>user" class="mt-4 inline-block rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white">Pilih produk</a>
        </div>
    <?php else: ?>
        <div class="mt-5 rounded-md bg-slate-100 p-4">
            <div class="flex justify-between"><span>Total</span><b>Rp<?= number_format($summary['total'], 0, ',', '.') ?></b></div>
        </div>
        <form method="post" class="mt-5 grid gap-4">
            <label class="grid gap-1 text-sm font-medium">Alamat pengiriman
                <textarea name="shipping_address" required class="min-h-28 rounded-md border border-slate-300 px-3 py-2"><?= htmlspecialchars(current_user()['address'] ?? '') ?></textarea>
            </label>
            <button class="rounded-md bg-emerald-700 px-4 py-3 font-semibold text-white">Bayar dan buat order</button>
        </form>
    <?php endif; ?>
</section>
