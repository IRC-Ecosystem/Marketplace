<?php

class Admin extends Controllers
{
    private function adminData(string $title): array
    {
        require_role('admin');
        $userModel = $this->model('User_model');
        $orderModel = $this->model('Order_model');
        $productModel = $this->model('Product_model');

        $stores = $this->model('Store_model')->all();
        $users = $userModel->all();
        $orders = $orderModel->all();
        $roleCounts = [];
        foreach ($userModel->countByRole() as $role) {
            $roleCounts[$role['role']] = (int) $role['total'];
        }
        $orderStatusCounts = [];
        foreach ($orders as $order) {
            $orderStatusCounts[$order['order_status']] = ($orderStatusCounts[$order['order_status']] ?? 0) + 1;
        }
        $storeStatusCounts = [];
        foreach ($stores as $store) {
            $storeStatusCounts[$store['status']] = ($storeStatusCounts[$store['status']] ?? 0) + 1;
        }

        return [
            'title' => $title,
            'users' => $users,
            'roles' => $userModel->countByRole(),
            'stores' => $stores,
            'orders' => $orders,
            'stats' => [
                'orders' => $orderModel->count(),
                'revenue' => $orderModel->revenue(),
                'products' => $productModel->count(),
                'low_stock' => $productModel->lowStockCount(),
                'stores' => count($stores),
            ],
            'charts' => [
                'roles' => $roleCounts,
                'order_status' => $orderStatusCounts,
                'store_status' => $storeStatusCounts,
                'platform_summary' => [
                    'orders' => $orderModel->count(),
                    'products' => $productModel->count(),
                    'stores' => count($stores),
                    'low_stock' => $productModel->lowStockCount(),
                ],
            ],
        ];
    }

    private function renderAdmin(string $view, string $title): void
    {
        $data = $this->adminData($title);
        $this->view('templates/header', $data);
        $this->view($view, $data);
        $this->view('templates/footer');
    }

    public function index()
    {
        $this->renderAdmin('admin/index', 'Dashboard Admin');
    }

    public function users()
    {
        $this->renderAdmin('admin/users', 'Manajemen User');
    }

    public function stores()
    {
        $this->renderAdmin('admin/stores', 'Monitoring Toko');
    }

    public function orders()
    {
        $this->renderAdmin('admin/orders', 'Monitoring Order');
    }

    public function sellerFeatures()
    {
        $this->renderAdmin('admin/seller_features', 'Monitoring Seller');
    }
}
