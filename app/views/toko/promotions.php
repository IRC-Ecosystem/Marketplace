<?php /** @var array $data */ ?>
<section class="mb-6"><h1 class="text-3xl font-bold">Promosi Toko</h1><p class="mt-2 text-slate-600">Kelola voucher toko, diskon produk, dan produk unggulan.</p></section>
<section class="grid gap-4 md:grid-cols-2">
    <?php foreach ($data['promotions'] as $promo): ?>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500"><?= htmlspecialchars($promo['type']) ?></p><h2 class="mt-1 text-xl font-bold"><?= htmlspecialchars($promo['name']) ?></h2><p class="mt-2 text-slate-600"><?= htmlspecialchars($promo['value']) ?></p><span class="mt-3 inline-block rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold"><?= htmlspecialchars($promo['status']) ?></span></div>
    <?php endforeach; ?>
</section>
