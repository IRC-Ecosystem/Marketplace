<?php /** @var array $data */ ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .chat-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .chat-scroll::-webkit-scrollbar {
        width: 4px;
    }

    .chat-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .chat-scroll::-webkit-scrollbar-thumb {
        background: #bcc9c6;
        border-radius: 9999px;
    }

    .chat-bubble-inbound {
        border-radius: 4px 16px 16px 16px;
    }

    .chat-bubble-outbound {
        border-radius: 16px 16px 4px 16px;
    }
</style>

<?php
$conversations = [
    ['name' => 'Toko Maju Jaya', 'desc' => 'Pertanyaan stok produk', 'time' => '10:42', 'unread' => 2, 'online' => true],
    ['name' => 'Batik Solo Mandiri', 'desc' => 'Terima kasih atas pesanannya...', 'time' => 'Kemarin', 'unread' => 0, 'online' => false],
    ['name' => 'Gayo Bean Co.', 'desc' => 'Pesanan kamu sedang diproses.', 'time' => 'Rabu', 'unread' => 0, 'online' => true],
    ['name' => 'PasarKita Support', 'desc' => 'Bantuan pembayaran dan order.', 'time' => 'Senin', 'unread' => 0, 'online' => true],
];
$featured = $data['featured'][0] ?? null;
?>

<div class="chat-page overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <section class="border-b border-slate-200 bg-white p-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-950">Chat Pelanggan</h1>
                <p class="mt-2 text-sm text-slate-600">Komunikasi pembeli dengan seller, support, dan layanan ekosistem PasarKita.</p>
            </div>
            <label class="relative block w-full lg:w-80">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold uppercase text-slate-400">Cari</span>
                <input class="w-full rounded-full border border-slate-300 bg-slate-50 py-3 pl-16 pr-4 text-sm focus:border-emerald-700 focus:ring-emerald-100" placeholder="Cari pesan atau toko..." type="text">
            </label>
        </div>
    </section>

    <div class="grid h-[720px] min-h-[620px] grid-cols-1 lg:grid-cols-[360px_1fr]">
        <aside class="flex min-h-0 flex-col border-b border-slate-200 bg-slate-50 lg:border-b-0 lg:border-r">
            <div class="flex items-center justify-between p-5">
                <h2 class="text-xl font-extrabold text-slate-950">Pesan Terbaru</h2>
                <button class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 hover:border-emerald-700 hover:text-emerald-700">Filter</button>
            </div>
            <div class="chat-scroll min-h-0 flex-1 overflow-y-auto">
                <?php foreach ($conversations as $index => $conversation): ?>
                    <button class="flex w-full items-start gap-4 border-b border-slate-200 p-5 text-left transition hover:bg-white <?= $index === 0 ? 'border-l-4 border-l-emerald-700 bg-emerald-50' : '' ?>">
                        <div class="relative shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full border <?= $index === 0 ? 'border-emerald-300 bg-emerald-700 text-white' : 'border-slate-200 bg-white text-slate-600' ?> text-sm font-extrabold">
                                <?= htmlspecialchars(strtoupper(substr($conversation['name'], 0, 2))) ?>
                            </div>
                            <span class="absolute bottom-0 right-0 h-3.5 w-3.5 rounded-full border-2 border-white <?= $conversation['online'] ? 'bg-emerald-600' : 'bg-slate-300' ?>"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="mb-1 flex items-start justify-between gap-3">
                                <h3 class="truncate text-sm font-extrabold text-slate-950"><?= htmlspecialchars($conversation['name']) ?></h3>
                                <span class="text-[10px] font-semibold text-slate-400"><?= htmlspecialchars($conversation['time']) ?></span>
                            </div>
                            <p class="truncate text-sm <?= $index === 0 ? 'font-semibold text-slate-700' : 'text-slate-500' ?>"><?= htmlspecialchars($conversation['desc']) ?></p>
                            <?php if ($conversation['unread']): ?>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="rounded-full bg-emerald-700 px-2 py-0.5 text-[10px] font-bold text-white"><?= (int) $conversation['unread'] ?></span>
                                    <span class="text-[10px] font-bold uppercase text-emerald-700">Produk ditanyakan</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>
        </aside>

        <section class="flex min-h-0 flex-col bg-white">
            <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-700 text-sm font-extrabold text-white">TM</div>
                        <span class="absolute -bottom-1 -right-1 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-600"></span>
                    </div>
                    <div>
                        <h2 class="text-sm font-extrabold text-slate-950">Toko Maju Jaya</h2>
                        <p class="text-xs text-emerald-700">Membalas cepat, sekitar 5 menit</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-slate-400">
                    <button class="rounded-lg p-2 hover:bg-white hover:text-emerald-700">Call</button>
                    <button class="rounded-lg p-2 hover:bg-white hover:text-emerald-700">Video</button>
                    <button class="rounded-lg p-2 hover:bg-white hover:text-emerald-700">Menu</button>
                </div>
            </div>

            <div class="chat-scroll min-h-0 flex-1 space-y-6 overflow-y-auto bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] p-5 [background-size:20px_20px]">
                <div class="flex justify-center">
                    <span class="rounded-full bg-slate-100 px-4 py-1 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Hari Ini</span>
                </div>

                <div class="flex max-w-[82%] items-end gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-200 text-[10px] font-bold text-slate-600">TM</div>
                    <div class="chat-bubble-inbound bg-slate-100 p-4 text-sm text-slate-800 shadow-sm">
                        <p>Selamat pagi Kak <?= htmlspecialchars(explode(' ', trim($data['user']['name'] ?? 'Budi'))[0]) ?>! Ada yang bisa kami bantu hari ini untuk produk UMKM kami?</p>
                        <span class="mt-2 block text-right text-[10px] text-slate-400">09:15</span>
                    </div>
                </div>

                <div class="ml-auto flex max-w-[82%] flex-col items-end gap-3">
                    <div class="chat-bubble-outbound bg-emerald-700 p-4 text-sm text-white shadow-md">
                        <p>Halo, apakah stok produk ini masih tersedia? Saya ingin membeli beberapa pcs untuk kebutuhan kantor.</p>
                        <span class="mt-2 block text-right text-[10px] text-emerald-100">09:20 - terkirim</span>
                    </div>

                    <?php if ($featured): ?>
                        <div class="w-72 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                            <div class="h-36 bg-slate-100">
                                <?php if (!empty($featured['image_url'])): ?>
                                    <img class="h-full w-full object-cover" src="<?= htmlspecialchars($featured['image_url']) ?>" alt="<?= htmlspecialchars($featured['name']) ?>">
                                <?php endif; ?>
                            </div>
                            <div class="p-4">
                                <h3 class="truncate text-sm font-extrabold text-slate-950"><?= htmlspecialchars($featured['name']) ?></h3>
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="font-extrabold text-emerald-700">Rp<?= number_format($featured['price'], 0, ',', '.') ?></span>
                                    <span class="text-[10px] text-slate-500">Stok <?= (int) $featured['stock'] ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex max-w-[82%] items-end gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-200 text-[10px] font-bold text-slate-600">TM</div>
                    <div class="chat-bubble-inbound bg-slate-100 p-4 text-sm text-slate-800 shadow-sm">
                        <div class="mb-3 flex gap-2 rounded-lg bg-white p-2 text-[11px] text-slate-500">
                            <div class="w-1 rounded-full bg-emerald-700"></div>
                            <span>Saya ingin membeli beberapa pcs...</span>
                        </div>
                        <p>Sebentar ya Kak, kami cek stok di gudang dulu. Mohon ditunggu 1-2 menit.</p>
                        <span class="mt-2 block text-right text-[10px] text-slate-400">10:42</span>
                    </div>
                </div>

                <div class="flex items-end gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-200 text-[10px] font-bold text-slate-600">TM</div>
                    <div class="flex items-center gap-1 rounded-full bg-slate-100 px-4 py-3">
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-slate-400"></span>
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-slate-400 [animation-delay:0.2s]"></span>
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-slate-400 [animation-delay:0.4s]"></span>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-200 bg-slate-50 p-5">
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm focus-within:border-emerald-700 focus-within:ring-2 focus-within:ring-emerald-100">
                    <div class="hidden items-center gap-2 border-r border-slate-200 pr-3 md:flex">
                        <button class="rounded-full px-2 py-1 text-sm font-bold text-slate-400 hover:bg-emerald-50 hover:text-emerald-700">+</button>
                        <button class="rounded-full px-2 py-1 text-sm font-bold text-slate-400 hover:bg-emerald-50 hover:text-emerald-700">Img</button>
                    </div>
                    <input class="flex-1 border-none bg-transparent text-sm placeholder:text-slate-400 focus:ring-0" placeholder="Ketik pesan di sini..." type="text">
                    <button class="rounded-xl bg-emerald-700 px-4 py-2 text-sm font-extrabold text-white hover:bg-emerald-800">Kirim</button>
                </div>
                <div class="mt-3 flex items-center justify-between text-[11px] text-slate-400">
                    <div class="flex gap-4">
                        <button class="font-bold text-emerald-700 hover:underline">Template Balasan</button>
                        <button class="hover:text-slate-600">Kirim Lokasi</button>
                    </div>
                    <span>Shift + Enter untuk baris baru</span>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    const chatArea = document.querySelector('.chat-scroll.min-h-0.flex-1');
    if (chatArea) {
        chatArea.scrollTop = chatArea.scrollHeight;
    }
</script>
