<?php

class User extends Controllers
{
    private function userData(string $title): array
    {
        require_role('user');
        $productModel = $this->model('Product_model');
        return [
            'title' => $title,
            'user' => $this->model('User_model')->find(current_user()['id']),
            'products' => $productModel->latest($_GET['q'] ?? null),
            'featured' => $productModel->featured(),
            'orders' => $this->model('Order_model')->byUser(current_user()['id']),
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

    public function cart()
    {
        require_role('user');
        $data['title'] = 'Keranjang';
        $data['summary'] = $this->model('Cart_model')->summary();

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
        $this->view('templates/header', $data);
        $this->view('user/checkout', $data);
        $this->view('templates/footer');
    }
}
