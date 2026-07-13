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
        $productCountsByStore = [];
        foreach ($productModel->countByStore() as $row) {
            $productCountsByStore[(int) $row['store_id']] = (int) $row['total'];
        }
        $revenueByStore = [];
        foreach ($orderModel->storeRevenueSummary() as $row) {
            $revenueByStore[(int) $row['store_id']] = [
                'revenue' => (float) $row['revenue'],
                'orders' => (int) $row['orders'],
            ];
        }
        $storeMetrics = [];
        foreach ($stores as $store) {
            $storeId = (int) $store['id'];
            $storeMetrics[$storeId] = [
                'products' => $productCountsByStore[$storeId] ?? 0,
                'revenue' => $revenueByStore[$storeId]['revenue'] ?? 0,
                'orders' => $revenueByStore[$storeId]['orders'] ?? 0,
            ];
        }
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
            'store_metrics' => $storeMetrics,
            'orders' => $orders,
            'low_stock_products' => $productModel->lowStockGlobal(),
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

    public function createUser()
    {
        require_role('admin');
        flash('error', 'Admin hanya memiliki akses monitoring pengguna.');
        $this->redirect('admin/users');
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
        $this->renderAdmin('admin/seller_features', 'Fitur Penjual');
    }
}
