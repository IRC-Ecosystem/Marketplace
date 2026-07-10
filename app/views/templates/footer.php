</main>
<?php if (current_user()): ?>
    </div>
</div>
<?php else: ?>
<footer class="border-t border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-slate-500 md:flex-row md:items-center md:justify-between">
        <span>PasarKita Marketplace UMKM</span>
        <span>Fee: Marketplace 2%, Gateway 0.5%, Bank 1%, Pajak 2%, Logistik 5%/Rp5.000</span>
    </div>
</footer>
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
