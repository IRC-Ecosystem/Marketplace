<?php
/** @var array $data */
$money = fn ($value) => 'Rp' . number_format((float) $value, 0, ',', '.');
$badge = fn ($status) => match ($status) { 'completed', 'paid' => 'bg-emerald-50 text-emerald-800', 'shipped' => 'bg-blue-50 text-blue-800', 'cancelled' => 'bg-red-50 text-red-800', default => 'bg-amber-50 text-amber-800' };
?>
<section class="mb-6"><h1 class="text-3xl font-bold">Manajemen Pesanan</h1><p class="mt-2 text-slate-600">Pesanan baru, perlu diproses, detail pesanan, status pembayaran, selesai, dan pembatalan.</p></section>
<section class="mb-6 rounded-lg border border-slate-200 bg-white p-5 shadow-sm" data-chart-url="<?= BASEURL ?>chart/sellerOrders"></section>
<section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
    <?php if (!$data['orders']): ?><div class="rounded-md border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">Belum ada order.</div><?php endif; ?>
    <?php foreach ($data['orders'] as $order): ?>
        <form method="post" action="<?= BASEURL ?>toko/orderStatus" class="mb-3 rounded-md border border-slate-200 p-4">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div><b><?= htmlspecialchars($order['order_code']) ?></b><p class="text-sm text-slate-600"><?= htmlspecialchars($order['customer_name'] ?? 'Pembeli') ?> - <?= htmlspecialchars($order['shipping_address']) ?></p></div>
                <div class="flex gap-2"><span class="rounded-md px-2 py-1 text-xs font-semibold <?= $badge($order['payment_status']) ?>"><?= htmlspecialchars($order['payment_status']) ?></span><span class="rounded-md px-2 py-1 text-xs font-semibold <?= $badge($order['order_status']) ?>"><?= htmlspecialchars($order['order_status']) ?></span></div>
            </div>
            <div class="mt-3 grid gap-3 md:grid-cols-[1fr_auto_auto] md:items-center">
                <p class="text-sm text-slate-600">Subtotal: <b><?= $money($order['subtotal']) ?></b> | Total: <b><?= $money($order['total']) ?></b></p>
                <select name="status" class="rounded border border-slate-300 px-3 py-2"><?php foreach (['processing', 'shipped', 'completed', 'cancelled'] as $status): ?><option value="<?= $status ?>" <?= $order['order_status'] === $status ? 'selected' : '' ?>><?= $status ?></option><?php endforeach; ?></select>
                <button class="rounded bg-emerald-700 px-3 py-2 font-semibold text-white">Proses</button>
            </div>
        </form>
    <?php endforeach; ?>
</section>
