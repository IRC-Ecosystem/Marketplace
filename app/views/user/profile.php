<?php /** @var array $data */ ?>
<section class="mb-6 rounded-xl border border-[#bcc9c6] bg-[#eff4ff] p-6">
    <p class="text-sm font-extrabold uppercase tracking-wide text-[#00685f]">Akun Customer</p>
    <h1 class="mt-2 text-3xl font-extrabold text-[#0b1c30]">Profil Pembeli</h1>
    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#3d4947]">Informasi akun, saldo wallet, dan ringkasan aktivitas belanja.</p>
</section>

<section class="grid gap-6 lg:grid-cols-[360px_1fr]">
    <aside class="rounded-xl border border-[#bcc9c6] bg-white p-6">
        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#00685f] text-2xl font-extrabold text-white">
            <?= htmlspecialchars(strtoupper(substr($data['user']['name'], 0, 1))) ?>
        </div>
        <h2 class="mt-4 text-xl font-extrabold text-[#0b1c30]"><?= htmlspecialchars($data['user']['name']) ?></h2>
        <p class="mt-1 text-sm text-[#3d4947]"><?= htmlspecialchars($data['user']['email']) ?></p>
        <span class="mt-4 inline-flex rounded-lg bg-[#89f5e7] px-3 py-2 text-xs font-extrabold text-[#00201d]">Pembeli Setia</span>
    </aside>

    <div class="grid gap-4 md:grid-cols-2">
        <article class="rounded-xl border border-[#bcc9c6] bg-white p-5">
            <p class="text-sm font-semibold text-[#3d4947]">Saldo SmartBank</p>
            <p class="mt-2 text-3xl font-extrabold text-[#00685f]">Rp<?= number_format($data['user']['balance'], 0, ',', '.') ?></p>
        </article>
        <article class="rounded-xl border border-[#bcc9c6] bg-white p-5">
            <p class="text-sm font-semibold text-[#3d4947]">Total Order</p>
            <p class="mt-2 text-3xl font-extrabold text-[#0b1c30]"><?= count($data['orders']) ?></p>
        </article>
        <article class="rounded-xl border border-[#bcc9c6] bg-white p-5 md:col-span-2">
            <h3 class="font-extrabold text-[#0b1c30]">Detail Kontak</h3>
            <div class="mt-4 grid gap-3 text-sm md:grid-cols-2">
                <div><span class="text-[#3d4947]">Telepon</span><p class="font-bold"><?= htmlspecialchars($data['user']['phone'] ?: '-') ?></p></div>
                <div><span class="text-[#3d4947]">Alamat</span><p class="font-bold"><?= htmlspecialchars($data['user']['address'] ?: '-') ?></p></div>
            </div>
        </article>
    </div>
</section>
