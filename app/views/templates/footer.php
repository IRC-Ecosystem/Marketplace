</main>
<?php $footerUser = current_user(); ?>
<footer class="border-t border-[#bcc9c6] bg-[#dce9ff]">
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8 px-6 py-12 text-[#0b1c30] md:grid-cols-4 lg:px-8">
        <div>
            <h3 class="text-2xl font-extrabold text-[#00685f]">PasarKita</h3>
            <p class="mt-4 max-w-xs text-sm leading-6 text-[#3d4947]">Pusat belanja online UMKM Indonesia terbaik dan terpercaya.</p>
        </div>

        <div>
            <h4 class="text-sm font-extrabold uppercase tracking-wide text-[#0b1c30]">Layanan</h4>
            <ul class="mt-4 space-y-3 text-sm text-[#3d4947]">
                <li><a class="transition hover:text-[#00685f]" href="<?= BASEURL ?>">LogistiKita</a></li>
                <li><a class="transition hover:text-[#00685f]" href="<?= BASEURL ?>">SmartBank</a></li>
                <li><a class="transition hover:text-[#00685f]" href="<?= BASEURL ?>">SupplierHub</a></li>
                <li><a class="transition hover:text-[#00685f]" href="<?= BASEURL ?>">UMKM Insight</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-sm font-extrabold uppercase tracking-wide text-[#0b1c30]">Bantuan</h4>
            <ul class="mt-4 space-y-3 text-sm text-[#3d4947]">
                <li><a class="transition hover:text-[#00685f]" href="<?= BASEURL ?>about">Pusat Bantuan</a></li>
                <li><a class="transition hover:text-[#00685f]" href="<?= BASEURL ?>about">Syarat &amp; Ketentuan</a></li>
                <li><a class="transition hover:text-[#00685f]" href="<?= BASEURL ?>about">Kebijakan Privasi</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-sm font-extrabold uppercase tracking-wide text-[#0b1c30]">Ikuti Kami</h4>
            <p class="mt-4 text-sm leading-6 text-[#3d4947]">Dukung ekosistem UMKM lokal dan temukan produk pilihan PasarKita.</p>
            <div class="mt-6 flex gap-3">
                <a href="<?= BASEURL ?>" class="flex h-9 w-9 items-center justify-center rounded-full bg-[#00685f] text-white shadow-sm transition hover:bg-[#005049]" aria-label="PasarKita sosial">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                    </svg>
                </a>
                <a href="<?= BASEURL ?>" class="flex h-9 w-9 items-center justify-center rounded-full bg-[#00685f] text-white shadow-sm transition hover:bg-[#005049]" aria-label="Bagikan PasarKita">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="18" cy="5" r="3"></circle>
                        <circle cx="6" cy="12" r="3"></circle>
                        <circle cx="18" cy="19" r="3"></circle>
                        <path d="m8.59 13.51 6.83 3.98"></path>
                        <path d="m15.41 6.51-6.82 3.98"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="border-t border-[#bcc9c6]/70">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-6 py-5 text-sm text-[#3d4947] md:flex-row md:items-center md:justify-between lg:px-8">
            <p>&copy; <?= date('Y') ?> PasarKita. Empowering Indonesian UMKM.</p>
            <p>Marketplace UMKM - SmartBank - LogistiKita - SupplierHub</p>
        </div>
    </div>
</footer>
<?php if (current_user()): ?>
    <?php
    $supportHref = match ($footerUser['role'] ?? null) {
        'seller' => BASEURL . 'toko/chat',
        'admin' => BASEURL . 'admin/sellerFeatures',
        default => BASEURL . 'user/chat',
    };
    ?>
    <a href="<?= $supportHref ?>" class="fixed bottom-6 right-6 z-50 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-700 text-white shadow-xl shadow-emerald-900/20 transition hover:scale-105 hover:bg-emerald-800" aria-label="Bantuan">
        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 11a6 6 0 0 0-12 0v4a3 3 0 0 0 3 3h1"/>
            <path d="M18 11v5a2 2 0 0 1-2 2h-3"/>
            <path d="M5 13H4a2 2 0 0 0 0 4h1"/>
            <path d="M19 13h1a2 2 0 0 1 0 4h-1"/>
            <path d="M9 18h5"/>
        </svg>
    </a>
    </div>
</div>
<?php endif; ?>
<script>
    (function () {
        const colorMap = {
            emerald: 'bg-emerald-600',
            amber: 'bg-amber-500',
            sky: 'bg-sky-600',
            red: 'bg-red-500',
            slate: 'bg-slate-600'
        };

        function loadingHtml() {
            return '<div class="grid gap-3">' +
                '<div class="h-4 w-1/3 animate-pulse rounded bg-slate-200"></div>' +
                '<div class="h-3 animate-pulse rounded bg-slate-200"></div>' +
                '<div class="h-3 w-5/6 animate-pulse rounded bg-slate-200"></div>' +
                '<div class="h-3 w-2/3 animate-pulse rounded bg-slate-200"></div>' +
            '</div>';
        }

        function renderChart(el, payload) {
            const items = payload.items || [];
            const max = Math.max(...items.map((item) => Number(item.value) || 0), 1);
            const rows = items.length ? items.map((item) => {
                const value = Number(item.value) || 0;
                const width = Math.max(5, (value / max) * 100);
                const color = colorMap[item.color] || colorMap.emerald;
                const shown = item.formatted || value;
                return '<div class="mt-4">' +
                    '<div class="mb-1 flex justify-between gap-3 text-sm">' +
                        '<span class="font-medium text-slate-700">' + escapeHtml(item.label) + '</span>' +
                        '<b class="text-slate-900">' + escapeHtml(String(shown)) + '</b>' +
                    '</div>' +
                    '<div class="h-3 overflow-hidden rounded-full bg-slate-100">' +
                        '<div class="h-3 rounded-full ' + color + '" style="width:' + width + '%"></div>' +
                    '</div>' +
                '</div>';
            }).join('') : '<div class="rounded-md border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">Belum ada data grafik.</div>';

            el.innerHTML = '<div class="mb-4 flex items-start justify-between gap-3">' +
                '<div><h2 class="text-xl font-bold">' + escapeHtml(payload.title || el.dataset.chartTitle || 'Grafik') + '</h2>' +
                '<p class="mt-1 text-sm text-slate-500">Data diambil langsung dari database dan diperbarui otomatis.</p></div>' +
                '<span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">Update ' + escapeHtml(payload.updated_at || '-') + '</span>' +
            '</div>' + rows;
        }

        function escapeHtml(value) {
            return value.replace(/[&<>"']/g, function (char) {
                return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'}[char];
            });
        }

        function loadChart(el) {
            el.innerHTML = loadingHtml();
            fetch(el.dataset.chartUrl, {credentials: 'include'})
                .then((response) => response.json())
                .then((payload) => renderChart(el, payload))
                .catch(() => {
                    el.innerHTML = '<div class="rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">Gagal memuat data grafik.</div>';
                });
        }

        document.querySelectorAll('[data-chart-url]').forEach((el) => {
            loadChart(el);
            window.setInterval(() => loadChart(el), Number(el.dataset.refreshMs || 15000));
        });
    })();
</script>
</body>
</html>
