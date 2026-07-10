<?php

class Toko extends Controllers
{
    private function store(): ?array
    {
        return $this->model('Store_model')->findByOwner(current_user()['id']);
    }

    private function sellerData(string $title): array
    {
        require_role('seller');
        $store = $this->store();
        if (!$store) {
            $this->redirect('toko');
        }

        $productModel = $this->model('Product_model');
        $orderModel = $this->model('Order_model');

        return [
            'title' => $title,
            'store' => $store,
            'products' => $productModel->byStore($store['id']),
            'orders' => $orderModel->byStore($store['id']),
            'orderItems' => $orderModel->itemsByStore($store['id']),
            'summary' => $orderModel->sellerSummary($store['id']),
            'bestSellers' => $orderModel->bestSellersByStore($store['id']),
            'lowStock' => $productModel->lowStockByStore($store['id']),
            'categories' => $productModel->categoriesByStore($store['id']),
            'promotions' => [
                ['name' => 'Voucher toko UMKM10', 'type' => 'Voucher', 'status' => 'draft', 'value' => '10%'],
                ['name' => 'Produk unggulan mingguan', 'type' => 'Highlight', 'status' => 'aktif', 'value' => 'Etalase utama'],
            ],
            'messages' => [
                ['buyer' => 'Budi Pembeli', 'message' => 'Apakah produk bisa dikirim hari ini?', 'time' => '09:15', 'unread' => true],
                ['buyer' => 'Rina', 'message' => 'Stok kopi masih ada?', 'time' => 'Kemarin', 'unread' => false],
            ],
        ];
    }

    private function renderSeller(string $view, string $title): void
    {
        $data = $this->sellerData($title);
        $this->view('templates/header', $data);
        $this->view($view, $data);
        $this->view('templates/footer');
    }

    public function index()
    {
        require_login();
        if (has_role('admin')) {
            $this->redirect('admin');
        }

        $store = $this->store();
        if (!$store) {
            $data['title'] = 'Buka Toko';
            $data['store'] = null;
            $this->view('templates/header', $data);
            $this->view('toko/index', $data);
            $this->view('templates/footer');
            return;
        }

        $this->renderSeller('toko/dashboard', 'Dashboard Seller');
    }

    public function products()
    {
        $this->renderSeller('toko/products', 'Manajemen Produk');
    }

    public function orders()
    {
        $this->renderSeller('toko/orders', 'Manajemen Pesanan');
    }

    public function promotions()
    {
        $this->renderSeller('toko/promotions', 'Promosi Toko');
    }

    public function chat()
    {
        $this->renderSeller('toko/chat', 'Chat Pembeli');
    }

    public function finance()
    {
        $this->renderSeller('toko/finance', 'Keuangan Seller');
    }

    public function restock()
    {
        $this->renderSeller('toko/restock', 'Restock SupplierHub');
    }

    public function performance()
    {
        $this->renderSeller('toko/performance', 'Performa Toko');
    }

    public function create()
    {
        require_role('user');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (trim($_POST['name'] ?? '') === '') {
                flash('error', 'Nama toko wajib diisi.');
            } elseif ($this->model('Store_model')->create(current_user()['id'], $_POST)) {
                $_SESSION['user']['role'] = 'seller';
                flash('success', 'Toko berhasil dibuat. Kamu sekarang punya akses seller.');
            } else {
                flash('error', 'Gagal membuat toko.');
            }
        }
        $this->redirect('toko');
    }

    public function product()
    {
        require_role('seller');
        $store = $this->store();
        if (!$store) {
            $this->redirect('toko');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $required = ['name', 'category', 'price', 'stock'];
            foreach ($required as $field) {
                if (!isset($_POST[$field]) || trim((string) $_POST[$field]) === '') {
                    flash('error', 'Data produk belum lengkap.');
                    $this->redirect('toko/products');
                }
            }
            $this->model('Product_model')->create($store['id'], $_POST);
            flash('success', 'Produk berhasil ditambahkan.');
        }

        $this->redirect('toko/products');
    }

    public function updateProduct($id)
    {
        require_role('seller');
        $store = $this->store();
        if ($store && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model('Product_model')->update((int) $id, $store['id'], $_POST);
            flash('success', 'Produk diperbarui.');
        }
        $this->redirect('toko/products');
    }

    public function deleteProduct($id)
    {
        require_role('seller');
        $store = $this->store();
        if ($store) {
            $this->model('Product_model')->delete((int) $id, $store['id']);
            flash('success', 'Produk dihapus.');
        }
        $this->redirect('toko/products');
    }

    public function orderStatus()
    {
        require_role('seller');
        $store = $this->store();
        if ($store && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model('Order_model')->updateStatus((int) $_POST['order_id'], $_POST['status'], $store['id']);
            flash('success', 'Status pesanan diperbarui.');
        }
        $this->redirect('toko/orders');
    }
}
