<?php
/** @var array $data */
$summary = $data['summary'] ?? [];
$money = fn ($value) => 'Rp' . number_format((float) $value, 0, ',', '.');
?>
<section class="mb-6"><h1 class="text-3xl font-bold">Keuangan Seller</h1><p class="mt-2 text-slate-600">Pendapatan, status pencairan dana, transaksi order, fee marketplace, dan saldo SmartBank.</p></section>
<section class="grid gap-4 md:grid-cols-4">
    <div class="rounded-lg border bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Pendapatan</p><b><?= $money($summary['total_pendapatan'] ?? 0) ?></b></div>
    <div class="rounded-lg border bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Pencairan</p><b>Menunggu SmartBank</b></div>
    <div class="rounded-lg border bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Fee marketplace</p><b><?= $money($summary['total_fee_marketplace'] ?? 0) ?></b></div>
    <div class="rounded-lg border bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Saldo penuh</p><b>SmartBank</b></div>
</section>
<section class="mt-6 rounded-lg border border-slate-200 bg-white p-5 shadow-sm" data-chart-url="<?= BASEURL ?>chart/sellerFinance"></section>
<section class="mt-6 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm"><table class="w-full text-sm"><thead class="bg-slate-100 text-slate-600"><tr><th class="p-3 text-left">Order</th><th class="p-3">Produk</th><th class="p-3 text-right">Qty</th><th class="p-3 text-right">Subtotal</th></tr></thead><tbody><?php foreach ($data['orderItems'] as $item): ?><tr class="border-t"><td class="p-3 font-semibold"><?= htmlspecialchars($item['order_code']) ?></td><td class="p-3"><?= htmlspecialchars($item['product_name']) ?></td><td class="p-3 text-right"><?= (int) $item['qty'] ?></td><td class="p-3 text-right font-semibold"><?= $money($item['subtotal']) ?></td></tr><?php endforeach; ?><?php if (!$data['orderItems']): ?><tr><td colspan="4" class="p-6 text-center text-slate-500">Belum ada transaksi.</td></tr><?php endif; ?></tbody></table></section>
