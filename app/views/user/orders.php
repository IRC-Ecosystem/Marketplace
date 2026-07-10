<?php /** @var array $data */ ?>
<section class="mb-6"><h1 class="text-3xl font-bold">Order Saya</h1><p class="mt-2 text-slate-600">Status order dan pembayaran pembeli.</p></section>
<section class="overflow-hidden rounded-lg border border-slate-200 bg-white">
    <?php if (!$data['orders']): ?><div class="p-8 text-center text-sm text-slate-500">Belum ada order.</div><?php else: ?>
    <table class="w-full text-left text-sm"><thead class="bg-slate-100 text-slate-600"><tr><th class="p-3">Kode</th><th class="p-3 text-right">Total</th><th class="p-3">Pembayaran</th><th class="p-3">Status</th><th class="p-3">Tanggal</th></tr></thead><tbody>
    <?php foreach ($data['orders'] as $order): ?><?php $statusClass = match ($order['order_status']) { 'completed' => 'bg-emerald-50 text-emerald-800', 'shipped' => 'bg-blue-50 text-blue-800', 'cancelled' => 'bg-red-50 text-red-800', default => 'bg-amber-50 text-amber-800' }; ?><tr class="border-t"><td class="p-3 font-semibold"><?= htmlspecialchars($order['order_code']) ?></td><td class="p-3 text-right font-semibold">Rp<?= number_format($order['total'], 0, ',', '.') ?></td><td class="p-3"><span class="rounded-md bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-800"><?= htmlspecialchars($order['payment_status']) ?></span></td><td class="p-3"><span class="rounded-md px-2 py-1 text-xs font-semibold <?= $statusClass ?>"><?= htmlspecialchars($order['order_status']) ?></span></td><td class="p-3 text-slate-600"><?= htmlspecialchars($order['created_at']) ?></td></tr><?php endforeach; ?>
    </tbody></table><?php endif; ?>
</section>
