<?php /** @var array $data */ ?>
<section class="mb-6"><h1 class="text-3xl font-bold">Monitoring Toko</h1><p class="mt-2 text-slate-600">Daftar toko seller dan status aktifnya.</p></section>
<section class="mb-6 grid gap-6 lg:grid-cols-[1fr_360px]">
    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm" data-chart-url="<?= BASEURL ?>chart/adminStores"></div>
    <aside class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><h2 class="text-xl font-bold">Analisis Toko</h2><p class="mt-2 text-sm text-slate-600">Grafik status toko membantu admin melihat berapa toko yang aktif dan perlu ditinjau.</p></aside>
</section>
<section class="grid gap-3 md:grid-cols-2"><?php foreach ($data['stores'] as $store): ?><div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-start justify-between gap-3"><div><b><?= htmlspecialchars($store['name']) ?></b><p class="text-sm text-slate-600"><?= htmlspecialchars($store['owner_name']) ?></p></div><span class="rounded-md bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-800"><?= htmlspecialchars($store['status']) ?></span></div></div><?php endforeach; ?><?php if (!$data['stores']): ?><div class="rounded-lg border border-dashed p-8 text-center text-sm text-slate-500">Belum ada toko.</div><?php endif; ?></section>
