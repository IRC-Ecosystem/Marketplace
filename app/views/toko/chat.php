<?php
/** @var array $data */
$store = $data['store'] ?? ['name' => 'Toko'];
$messages = $data['messages'] ?? [];
$products = $data['products'] ?? [];
$orders = $data['orders'] ?? [];
$unreadCount = count(array_filter($messages, static fn ($message) => !empty($message['unread'])));
$activeMessage = $messages[0] ?? ['buyer' => 'Pembeli', 'message' => 'Belum ada pesan.', 'time' => '-', 'unread' => false];
$activeProduct = $products[0] ?? null;
$buyerStats = [
    'orders' => max(1, count($orders)),
    'cancelled' => count(array_filter($orders, static fn ($order) => ($order['order_status'] ?? '') === 'cancelled')),
];
$money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
$initial = static fn (string $name): string => strtoupper(substr(trim($name) ?: 'P', 0, 1));
?>

<style>
    .chat-bubble-left {
        border-radius: 4px 16px 16px 16px;
    }
    .chat-bubble-right {
        border-radius: 16px 4px 16px 16px;
    }
    .seller-chat-panel {
        height: calc(100vh - 190px);
        min-height: 620px;
    }
</style>

<section class="space-y-5">
    <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0b1c30]">Pesan & Chat</h1>
            <p class="mt-2 text-sm leading-6 text-[#3d4947]">Kelola percakapan pembeli, jawab pertanyaan stok, dan bagikan produk dari satu halaman.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="rounded-full bg-[#00685f]/10 px-4 py-2 text-sm font-extrabold text-[#00685f]"><?= $unreadCount ?> Baru</span>
            <a href="<?= BASEURL ?>toko/products" class="rounded-lg border border-[#00685f] px-4 py-3 text-sm font-extrabold text-[#00685f] transition hover:bg-[#00685f]/5">Bagikan Produk</a>
        </div>
    </div>

    <div class="seller-chat-panel grid overflow-hidden rounded-xl border border-[#bcc9c6] bg-white shadow-sm lg:grid-cols-[340px_minmax(0,1fr)] xl:grid-cols-[340px_minmax(0,1fr)_320px]">
        <aside class="flex min-h-0 flex-col border-b border-[#bcc9c6] bg-[#ffffff] lg:border-b-0 lg:border-r">
            <div class="space-y-4 border-b border-[#bcc9c6] p-4">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-[#6d7a77]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
                    <input id="chatSearch" class="w-full rounded-lg border border-[#bcc9c6] bg-[#eff4ff] py-3 pl-10 pr-4 text-sm outline-none transition focus:border-[#00685f] focus:ring-2 focus:ring-[#00685f]/20" placeholder="Cari pembeli..." type="text">
                </div>
                <div class="flex gap-2">
                    <button type="button" data-chat-filter="all" class="chat-filter flex-1 rounded-full bg-[#00685f]/10 py-2 text-xs font-extrabold text-[#00685f]">Semua</button>
                    <button type="button" data-chat-filter="unread" class="chat-filter flex-1 rounded-full py-2 text-xs font-extrabold text-[#3d4947] transition hover:bg-[#eff4ff]">Belum Dibaca</button>
                    <button type="button" data-chat-filter="pinned" class="chat-filter flex-1 rounded-full py-2 text-xs font-extrabold text-[#3d4947] transition hover:bg-[#eff4ff]">Tersemat</button>
                </div>
            </div>

            <div id="conversationList" class="min-h-0 flex-1 overflow-y-auto">
                <?php foreach ($messages as $index => $message): ?>
                    <?php
                    $buyer = (string) $message['buyer'];
                    $isUnread = !empty($message['unread']);
                    $active = $index === 0;
                    ?>
                    <button type="button"
                        class="conversation-item flex w-full items-center gap-4 border-l-4 px-4 py-4 text-left transition <?= $active ? 'border-[#00685f] bg-[#008378]/10' : 'border-transparent hover:bg-[#eff4ff]' ?>"
                        data-buyer="<?= htmlspecialchars($buyer, ENT_QUOTES, 'UTF-8') ?>"
                        data-message="<?= htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8') ?>"
                        data-time="<?= htmlspecialchars($message['time'], ENT_QUOTES, 'UTF-8') ?>"
                        data-unread="<?= $isUnread ? '1' : '0' ?>"
                        data-search="<?= htmlspecialchars(strtolower($buyer . ' ' . $message['message']), ENT_QUOTES, 'UTF-8') ?>">
                        <div class="relative shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full border border-[#bcc9c6] bg-[#00685f] text-lg font-extrabold text-white"><?= htmlspecialchars($initial($buyer)) ?></div>
                            <span class="absolute bottom-0 right-0 h-3.5 w-3.5 rounded-full border-2 border-white <?= $active ? 'bg-green-500' : 'bg-[#bcc9c6]' ?>"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <h2 class="truncate text-sm font-extrabold text-[#0b1c30]"><?= htmlspecialchars($buyer) ?></h2>
                                <span class="shrink-0 text-[10px] font-bold text-[#6d7a77]"><?= htmlspecialchars($message['time']) ?></span>
                            </div>
                            <p class="<?= $isUnread ? 'font-extrabold text-[#0b1c30]' : 'text-[#6d7a77]' ?> mt-1 truncate text-sm"><?= htmlspecialchars($message['message']) ?></p>
                        </div>
                        <?php if ($isUnread): ?>
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#00685f] text-[10px] font-extrabold text-white">1</span>
                        <?php endif; ?>
                    </button>
                <?php endforeach; ?>
                <?php if (!$messages): ?>
                    <div class="p-8 text-center text-sm text-[#6d7a77]">Belum ada chat pembeli.</div>
                <?php endif; ?>
            </div>
        </aside>

        <main class="flex min-h-0 min-w-0 flex-col bg-[#f8f9ff]">
            <header class="flex h-16 shrink-0 items-center justify-between border-b border-[#bcc9c6] bg-white px-5">
                <div class="flex items-center gap-3">
                    <div id="activeBuyerInitial" class="flex h-10 w-10 items-center justify-center rounded-full bg-[#00685f] font-extrabold text-white"><?= htmlspecialchars($initial($activeMessage['buyer'])) ?></div>
                    <div>
                        <h2 id="activeBuyerName" class="font-extrabold text-[#0b1c30]"><?= htmlspecialchars($activeMessage['buyer']) ?></h2>
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                            <p class="text-[10px] font-extrabold uppercase tracking-wide text-[#6d7a77]">Online</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="rounded-full p-2 text-[#3d4947] transition hover:bg-[#eff4ff]" aria-label="Cari chat">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
                    </button>
                    <button class="rounded-full p-2 text-[#3d4947] transition hover:bg-[#eff4ff]" aria-label="Telepon pembeli">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L8 9.74a16 16 0 0 0 6.26 6.26l1.28-1.28a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92z"></path></svg>
                    </button>
                    <button class="rounded-full p-2 text-[#3d4947] transition hover:bg-[#eff4ff]" aria-label="Menu chat">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.8"></circle><circle cx="12" cy="12" r="1.8"></circle><circle cx="12" cy="19" r="1.8"></circle></svg>
                    </button>
                </div>
            </header>

            <div id="chatMessages" class="min-h-0 flex-1 space-y-6 overflow-y-auto bg-slate-50 p-5">
                <div class="flex justify-center">
                    <span class="rounded-full bg-[#bcc9c6]/30 px-4 py-1 text-xs font-bold text-[#6d7a77]">Hari Ini</span>
                </div>

                <div class="flex items-end gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#00685f] text-xs font-extrabold text-white buyer-mini-initial"><?= htmlspecialchars($initial($activeMessage['buyer'])) ?></div>
                    <div class="max-w-[75%]">
                        <div class="chat-bubble-left border border-[#bcc9c6]/40 bg-white p-4 shadow-sm">
                            <p id="activeBuyerMessage" class="text-sm leading-6 text-[#0b1c30]"><?= htmlspecialchars($activeMessage['message']) ?></p>
                        </div>
                        <span id="activeBuyerTime" class="mt-1 block text-[10px] font-bold text-[#6d7a77]"><?= htmlspecialchars($activeMessage['time']) ?></span>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-1">
                    <div class="max-w-[75%]">
                        <div class="chat-bubble-right bg-[#00685f] p-4 shadow-md">
                            <p class="text-sm leading-6 text-white">Halo Kak! Terima kasih sudah menghubungi <?= htmlspecialchars($store['name']) ?>. Stok produk masih bisa kami cek dan bantu proses hari ini.</p>
                        </div>
                        <div class="mt-1 flex justify-end gap-1">
                            <span class="text-[10px] font-bold text-[#6d7a77]">12:43</span>
                            <span class="text-xs font-extrabold text-[#00685f]">dibaca</span>
                        </div>
                    </div>
                </div>

                <?php if ($activeProduct): ?>
                    <div class="flex flex-col items-end gap-1">
                        <div class="chat-bubble-right w-full max-w-[300px] overflow-hidden border border-[#bcc9c6] bg-white shadow-md">
                            <div class="h-32 bg-[#eff4ff]">
                                <?php if (!empty($activeProduct['image_url'])): ?>
                                    <img src="<?= htmlspecialchars($activeProduct['image_url']) ?>" alt="<?= htmlspecialchars($activeProduct['name']) ?>" class="h-full w-full object-cover">
                                <?php else: ?>
                                    <div class="flex h-full items-center justify-center text-[#00685f]">
                                        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="p-4">
                                <h3 class="truncate font-extrabold text-[#0b1c30]"><?= htmlspecialchars($activeProduct['name']) ?></h3>
                                <p class="mt-1 text-lg font-extrabold text-[#00685f]"><?= $money($activeProduct['price']) ?></p>
                                <button class="mt-3 w-full rounded-lg bg-[#00685f]/10 py-2 text-sm font-extrabold text-[#00685f]">Bagikan Produk</button>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-[#6d7a77]">12:45</span>
                    </div>
                <?php endif; ?>

                <div class="flex items-end gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#00685f] text-xs font-extrabold text-white buyer-mini-initial"><?= htmlspecialchars($initial($activeMessage['buyer'])) ?></div>
                    <div class="max-w-[75%]">
                        <div class="chat-bubble-left border border-[#bcc9c6]/40 bg-white p-4 shadow-sm">
                            <p class="text-sm leading-6 text-[#0b1c30]">Oke siap min, saya cek produk dan langsung checkout ya.</p>
                        </div>
                        <span class="mt-1 block text-[10px] font-bold text-[#6d7a77]">12:45</span>
                    </div>
                </div>
            </div>

            <footer class="sticky bottom-0 z-10 shrink-0 border-t border-[#bcc9c6] bg-white p-4 shadow-[0_-10px_30px_rgba(11,28,48,0.08)]">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-wide text-[#00685f]">Balas Pembeli</p>
                        <p class="text-xs text-[#6d7a77]">Tulis balasan untuk percakapan yang sedang aktif.</p>
                    </div>
                    <span class="hidden rounded-full bg-[#eff4ff] px-3 py-1 text-xs font-bold text-[#3d4947] sm:inline">Enter untuk baris baru</span>
                </div>
                <div class="flex items-end gap-3">
                    <div class="flex gap-1 pb-1">
                        <button class="flex h-10 w-10 items-center justify-center rounded-full text-[#3d4947] transition hover:bg-[#eff4ff]" aria-label="Tambah lampiran">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v8M8 12h8"></path></svg>
                        </button>
                        <button class="flex h-10 w-10 items-center justify-center rounded-full text-[#3d4947] transition hover:bg-[#eff4ff]" aria-label="Kirim gambar">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
                        </button>
                    </div>
                    <div class="relative flex-1">
                        <textarea id="sellerChatInput" class="max-h-32 w-full resize-none rounded-xl border border-[#bcc9c6] bg-[#eff4ff] p-3 pr-12 text-sm outline-none transition focus:border-[#00685f] focus:ring-2 focus:ring-[#00685f]/20" placeholder="Ketik pesan..." rows="1"></textarea>
                        <button class="absolute bottom-3 right-3 text-[#00685f]" aria-label="Emoji">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><path d="M9 9h.01M15 9h.01"></path></svg>
                        </button>
                    </div>
                    <button id="sellerChatSend" class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#00685f] text-white shadow-lg transition hover:bg-[#005049] active:scale-95" aria-label="Kirim pesan">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M2 21l21-9L2 3v7l15 2-15 2v7Z"></path></svg>
                    </button>
                </div>
            </footer>
        </main>

        <aside class="hidden min-h-0 flex-col overflow-y-auto border-l border-[#bcc9c6] bg-white p-5 xl:flex">
            <div class="border-b border-[#bcc9c6] pb-6 text-center">
                <div id="detailBuyerInitial" class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-[#00685f] text-3xl font-extrabold text-white"><?= htmlspecialchars($initial($activeMessage['buyer'])) ?></div>
                <h2 id="detailBuyerName" class="mt-4 text-xl font-extrabold text-[#0b1c30]"><?= htmlspecialchars($activeMessage['buyer']) ?></h2>
                <p class="mt-1 text-sm text-[#6d7a77]">Pelanggan PasarKita</p>
                <div class="mt-4 flex justify-center gap-2">
                    <button class="rounded-full bg-[#eff4ff] px-4 py-2 text-xs font-extrabold text-[#0b1c30]">Lihat Profil</button>
                    <button class="flex h-9 w-9 items-center justify-center rounded-full border border-[#bcc9c6] text-[#3d4947]" aria-label="Blokir">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="m4.9 4.9 14.2 14.2"></path></svg>
                    </button>
                </div>
            </div>

            <div class="mt-6 space-y-5">
                <section>
                    <h3 class="text-xs font-extrabold uppercase tracking-wide text-[#6d7a77]">Statistik Pembelian</h3>
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <div class="rounded-xl border border-[#bcc9c6] bg-[#f8f9ff] p-4">
                            <p class="text-xs font-bold text-[#6d7a77]">Total Order</p>
                            <p class="mt-1 text-2xl font-extrabold text-[#0b1c30]"><?= (int) $buyerStats['orders'] ?></p>
                        </div>
                        <div class="rounded-xl border border-[#bcc9c6] bg-[#f8f9ff] p-4">
                            <p class="text-xs font-bold text-[#6d7a77]">Pembatalan</p>
                            <p class="mt-1 text-2xl font-extrabold text-[#ba1a1a]"><?= (int) $buyerStats['cancelled'] ?></p>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-extrabold uppercase tracking-wide text-[#6d7a77]">Catatan Penjual</h3>
                        <button class="text-[#00685f]" aria-label="Edit catatan">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path></svg>
                        </button>
                    </div>
                    <div class="mt-3 rounded-xl border border-[#825100]/20 bg-[#ffddb8]/40 p-4">
                        <p class="text-sm italic leading-6 text-[#3d4947]">Pelanggan aktif. Prioritaskan balasan cepat untuk pertanyaan stok dan estimasi pengiriman.</p>
                    </div>
                </section>

                <section>
                    <h3 class="text-xs font-extrabold uppercase tracking-wide text-[#6d7a77]">Aksi Cepat</h3>
                    <div class="mt-3 space-y-2">
                        <a href="<?= BASEURL ?>toko/promotions" class="flex items-center gap-3 rounded-xl border border-[#bcc9c6] p-4 text-sm font-extrabold text-[#0b1c30] transition hover:bg-[#eff4ff]">
                            <svg class="h-5 w-5 text-[#00685f]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 9a3 3 0 0 1 3-3h14a3 3 0 0 1 3 3v2a2 2 0 0 0 0 4v2a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-2a2 2 0 0 0 0-4V9Z"></path></svg>
                            Beri Voucher Toko
                        </a>
                        <a href="<?= BASEURL ?>toko/orders" class="flex items-center gap-3 rounded-xl border border-[#bcc9c6] p-4 text-sm font-extrabold text-[#0b1c30] transition hover:bg-[#eff4ff]">
                            <svg class="h-5 w-5 text-[#00685f]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"></path><path d="M18 17V9M13 17V5M8 17v-3"></path></svg>
                            Riwayat Transaksi
                        </a>
                    </div>
                </section>
            </div>
        </aside>
    </div>
</section>

<script>
    (() => {
        const textarea = document.getElementById('sellerChatInput');
        const send = document.getElementById('sellerChatSend');
        const messages = document.getElementById('chatMessages');
        const search = document.getElementById('chatSearch');
        const conversations = document.querySelectorAll('.conversation-item');
        const filters = document.querySelectorAll('.chat-filter');
        let activeFilter = 'all';

        function initial(name) {
            return (name.trim()[0] || 'P').toUpperCase();
        }

        function setBuyer(button) {
            const buyer = button.dataset.buyer || 'Pembeli';
            const message = button.dataset.message || '';
            const time = button.dataset.time || '-';
            document.getElementById('activeBuyerName').textContent = buyer;
            document.getElementById('activeBuyerInitial').textContent = initial(buyer);
            document.getElementById('activeBuyerMessage').textContent = message;
            document.getElementById('activeBuyerTime').textContent = time;
            document.getElementById('detailBuyerName').textContent = buyer;
            document.getElementById('detailBuyerInitial').textContent = initial(buyer);
            document.querySelectorAll('.buyer-mini-initial').forEach((el) => el.textContent = initial(buyer));

            conversations.forEach((item) => {
                item.classList.remove('border-[#00685f]', 'bg-[#008378]/10');
                item.classList.add('border-transparent');
            });
            button.classList.add('border-[#00685f]', 'bg-[#008378]/10');
            button.classList.remove('border-transparent');
        }

        function applyConversationFilter() {
            const keyword = search.value.trim().toLowerCase();
            conversations.forEach((item) => {
                const matchesSearch = !keyword || item.dataset.search.includes(keyword);
                const matchesFilter = activeFilter === 'all' || (activeFilter === 'unread' && item.dataset.unread === '1') || activeFilter === 'pinned';
                item.classList.toggle('hidden', !(matchesSearch && matchesFilter));
            });
        }

        textarea?.addEventListener('input', () => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        });

        send?.addEventListener('click', () => {
            const text = textarea.value.trim();
            if (!text) return;
            messages.insertAdjacentHTML('beforeend', '<div class="flex flex-col items-end gap-1"><div class="max-w-[75%]"><div class="chat-bubble-right bg-[#00685f] p-4 shadow-md"><p class="text-sm leading-6 text-white"></p></div><div class="mt-1 flex justify-end gap-1"><span class="text-[10px] font-bold text-[#6d7a77]">Baru saja</span><span class="text-xs font-extrabold text-[#00685f]">terkirim</span></div></div></div>');
            messages.lastElementChild.querySelector('p').textContent = text;
            textarea.value = '';
            textarea.style.height = 'auto';
            messages.scrollTop = messages.scrollHeight;
        });

        conversations.forEach((item) => item.addEventListener('click', () => setBuyer(item)));
        search?.addEventListener('input', applyConversationFilter);

        filters.forEach((button) => {
            button.addEventListener('click', () => {
                activeFilter = button.dataset.chatFilter;
                filters.forEach((item) => {
                    item.classList.remove('bg-[#00685f]/10', 'text-[#00685f]');
                    item.classList.add('text-[#3d4947]');
                });
                button.classList.add('bg-[#00685f]/10', 'text-[#00685f]');
                button.classList.remove('text-[#3d4947]');
                applyConversationFilter();
            });
        });
    })();
</script>
