<?php /** @var array $data */ ?>
<section class="mb-6"><h1 class="text-3xl font-bold">Chat Pembeli</h1><p class="mt-2 text-slate-600">Notifikasi pesan masuk dan riwayat komunikasi pembeli-seller.</p></section>
<section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
    <?php foreach ($data['messages'] as $message): ?>
        <div class="mb-3 flex items-start justify-between gap-3 rounded-md border border-slate-200 p-4"><div><b><?= htmlspecialchars($message['buyer']) ?></b><p class="text-sm text-slate-600"><?= htmlspecialchars($message['message']) ?></p></div><div class="text-right"><p class="text-xs text-slate-500"><?= htmlspecialchars($message['time']) ?></p><?php if ($message['unread']): ?><span class="mt-2 inline-block rounded-md bg-red-50 px-2 py-1 text-xs font-semibold text-red-700">baru</span><?php endif; ?></div></div>
    <?php endforeach; ?>
</section>
