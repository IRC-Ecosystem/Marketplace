<?php

class User extends Controllers
{
    private function userData(string $title): array
    {
        require_role('user');
        $productModel = $this->model('Product_model');
        $minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float) $_GET['min_price'] : null;
        $maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float) $_GET['max_price'] : null;
        $products = $productModel->latest($_GET['q'] ?? null, $minPrice, $maxPrice);

        $orders = $this->model('Order_model')->byUser(current_user()['id']);
        $orderItems = [];
        foreach ($this->model('Order_model')->itemsByUser(current_user()['id']) as $item) {
            $orderItems[$item['order_id']][] = $item;
        }

        return [
            'title' => $title,
            'user' => $this->model('User_model')->find(current_user()['id']),
            'products' => $products,
            'featured' => $productModel->featured(),
            'orders' => $orders,
            'orderItems' => $orderItems,
            'cart' => $this->model('Cart_model')->summary(),
            'categories' => $productModel->allCategories(),
            'vouchers' => [
                ['code' => 'UMKM10', 'label' => 'Diskon 10% produk unggulan toko'],
                ['code' => 'ONGKIR5K', 'label' => 'Subsidi logistik untuk checkout pertama'],
            ],
        ];
    }

    private function renderUser(string $view, string $title): void
    {
        $data = $this->userData($title);
        $this->view('templates/header', $data);
        $this->view($view, $data);
        $this->view('templates/footer');
    }

    public function index()
    {
        $this->renderUser('user/index', 'Dashboard Pembeli');
    }

    public function catalog()
    {
        $this->renderUser('user/catalog', 'Katalog Produk');
    }

    public function orders()
    {
        $this->renderUser('user/orders', 'Order Saya');
    }

    public function chat()
    {
        $this->renderUser('user/chat', 'Chat Bantuan');
    }

    public function profile()
    {
        $this->renderUser('user/profile', 'Profil Pembeli');
    }

    public function cart()
    {
        require_role('user');
        $data['title'] = 'Keranjang';
        $data['summary'] = $this->model('Cart_model')->summary();
        $data['user'] = $this->model('User_model')->find(current_user()['id']);

        $this->view('templates/header', $data);
        $this->view('user/cart', $data);
        $this->view('templates/footer');
    }

    public function addCart()
    {
        require_role('user');
        $this->model('Cart_model')->add((int) ($_POST['product_id'] ?? 0), (int) ($_POST['qty'] ?? 1));
        flash('success', 'Produk masuk keranjang.');
        $this->back();
    }

    public function removeCart($productId)
    {
        require_role('user');
        $this->model('Cart_model')->remove((int) $productId);
        $this->redirect('user/cart');
    }

    public function checkout()
    {
        require_role('user');
        $cart = $this->model('Cart_model');
        $summary = $cart->summary();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $address = trim($_POST['shipping_address'] ?? '');
            if ($address === '') {
                flash('error', 'Alamat pengiriman wajib diisi.');
            } else {
                $orderId = $this->model('Order_model')->checkout(current_user()['id'], $address, $summary);
                if ($orderId) {
                    $cart->clear();
                    flash('success', 'Checkout berhasil. Payment request SmartBank tersimulasi dan ledger tercatat.');
                    $this->redirect('user');
                }
                flash('error', 'Checkout gagal. Cek saldo, stok, atau isi keranjang.');
            }
        }

        $data['title'] = 'Checkout';
        $data['summary'] = $summary;
        $data['user'] = $this->model('User_model')->find(current_user()['id']);
        $this->view('templates/header', $data);
        $this->view('user/checkout', $data);
        $this->view('templates/footer');
    }
}
