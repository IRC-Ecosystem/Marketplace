<?php

class User extends Controllers
{
    private function smartBankState(int $userId): array
    {
        $state = ['linked' => false];
        try {
            $state['linked'] = (bool) $this->model('SmartBank_model')->linkage('marketplace-user-' . $userId);
        } catch (Throwable $error) {
            $state['error'] = $error->getMessage();
        }
        return $state;
    }

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

        $smartBank = $this->smartBankState(current_user()['id']);

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
            'smartBank' => $smartBank,
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
                    flash('success', 'Order dibuat. Otorisasi pembayaran SmartBank dari halaman Order Saya.');
                    $this->redirect('user/orders');
                }
                flash('error', 'Checkout gagal. Cek saldo, stok, atau isi keranjang.');
            }
        }

        $data['title'] = 'Checkout';
        $data['summary'] = $summary;
        $data['user'] = $this->model('User_model')->find(current_user()['id']);
        $data['smartBank'] = $this->smartBankState(current_user()['id']);
        $this->view('templates/header', $data);
        $this->view('user/checkout', $data);
        $this->view('templates/footer');
    }

    public function smartbankOtpRequest()
    {
        require_role('user');
        $user = $this->model('User_model')->find(current_user()['id']);
        try {
            if (empty($user['phone'])) throw new RuntimeException('Nomor telepon profil wajib diisi.');
            $result = $this->model('SmartBank_model')->requestOtp($user['phone'], 'buyer-' . $user['id']);
            $_SESSION['smartbank_buyer_link'] = ['request_id' => $result['request_id']];
            flash('success', 'OTP dikirim ke Inbox SmartBank.');
        } catch (Throwable $error) {
            flash('error', $error->getMessage());
        }
        $this->redirect('user/profile');
    }

    public function smartbankOtpVerify()
    {
        require_role('user');
        $requestId = $_SESSION['smartbank_buyer_link']['request_id'] ?? '';
        $code = trim($_POST['code'] ?? '');
        try {
            if (!preg_match('/^\d{6}$/', $code) || $requestId === '') throw new RuntimeException('OTP tidak valid.');
            $result = $this->model('SmartBank_model')->verifyOtp($requestId, $code, 'buyer-' . current_user()['id']);
            $_SESSION['smartbank_buyer_link']['verification_token'] = $result['verification_token'];
            flash('success', 'OTP valid. Konfirmasi tautan wallet.');
        } catch (Throwable $error) {
            flash('error', $error->getMessage());
        }
        $this->redirect('user/profile');
    }

    public function smartbankLink()
    {
        require_role('user');
        try {
            $token = $_SESSION['smartbank_buyer_link']['verification_token'] ?? '';
            if ($token === '') throw new RuntimeException('Verifikasi OTP terlebih dahulu.');
            $this->model('SmartBank_model')->link('marketplace-user-' . current_user()['id'], $token);
            unset($_SESSION['smartbank_buyer_link']);
            flash('success', 'Wallet SmartBank berhasil ditautkan.');
        } catch (Throwable $error) {
            flash('error', $error->getMessage());
        }
        $this->redirect('user/profile');
    }

    public function paySmartBank($orderId)
    {
        require_role('user');
        try {
            $pin = trim($_POST['pin'] ?? '');
            if (!preg_match('/^\d{6}$/', $pin)) throw new RuntimeException('PIN SmartBank harus 6 digit.');
            $orders = $this->model('Order_model');
            $order = $orders->findByUser((int) $orderId, current_user()['id']);
            if (!$order || $order['payment_status'] !== 'pending') throw new RuntimeException('Order tidak siap dibayar.');
            $result = $this->model('SmartBank_model')->pay($order, current_user()['id'], $pin);
            $this->model('SmartBank_model')->recordPayment((int) $orderId, $result);
            $orders->markSmartBankPaid((int) $orderId, current_user()['id'], $result);
            flash('success', 'Pembayaran SmartBank berhasil.');
        } catch (Throwable $error) {
            flash('error', $error->getMessage());
        }
        $this->redirect('user/orders');
    }
}
