<?php

class Order_model
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function checkout(int $userId, string $address, array $summary): ?int
    {
        if (empty($summary['items'])) {
            return null;
        }

        $this->db->beginTransaction();
        try {
            $orderCode = 'PK-' . date('YmdHis') . '-' . random_int(100, 999);
            $stmt = $this->db->prepare('INSERT INTO orders (user_id, order_code, shipping_address, subtotal, marketplace_fee, gateway_fee, bank_fee, tax, shipping_fee, total, payment_status, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "pending", "processing")');
            $stmt->execute([
                $userId,
                $orderCode,
                $address,
                $summary['subtotal'],
                $summary['marketplaceFee'],
                $summary['gatewayFee'],
                $summary['bankFee'],
                $summary['tax'],
                $summary['shipping'],
                $summary['total'],
            ]);
            $orderId = (int) $this->db->lastInsertId();

            $itemStmt = $this->db->prepare('INSERT INTO order_items (order_id, product_id, store_id, product_name, price, qty, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stockStmt = $this->db->prepare('UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?');
            foreach ($summary['items'] as $item) {
                $product = $item['product'];
                $itemStmt->execute([$orderId, $product['id'], $product['store_id'], $product['name'], $product['price'], $item['qty'], $item['subtotal']]);
                $stockStmt->execute([$item['qty'], $product['id'], $item['qty']]);
            }

            $this->db->prepare('INSERT INTO payment_requests (order_id, from_app, user_id, amount, status, metadata) VALUES (?, "PasarKita", ?, ?, "pending", ?)')->execute([
                $orderId,
                $userId,
                $summary['total'],
                json_encode(['gateway' => 'smartbank_connector']),
            ]);

            $this->db->commit();
            return $orderId;
        } catch (Throwable $e) {
            $this->db->rollBack();
            return null;
        }
    }

    public function byUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findByUser(int $orderId, int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1');
        $stmt->execute([$orderId, $userId]);
        return $stmt->fetch() ?: null;
    }

    public function markSmartBankPaid(int $orderId, int $userId, array $result): void
    {
        $this->db->beginTransaction();
        try {
            $this->db->prepare('UPDATE orders SET payment_status = "paid" WHERE id = ? AND user_id = ? AND payment_status = "pending"')->execute([$orderId, $userId]);
            $this->db->prepare('UPDATE payment_requests SET status = "success", metadata = ? WHERE order_id = ?')->execute([json_encode($result), $orderId]);
            $this->db->prepare('INSERT INTO ledgers (user_id, order_id, type, amount, description) SELECT user_id, id, "debit", total, CONCAT("SmartBank PasarKita ", order_code) FROM orders WHERE id = ? AND user_id = ?')->execute([$orderId, $userId]);
            $this->db->commit();
        } catch (Throwable $error) {
            $this->db->rollBack();
            throw $error;
        }
    }

    public function itemsByUser(int $userId): array
    {
        $stmt = $this->db->prepare('
            SELECT oi.*, p.image_url, p.category, o.user_id
            FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            LEFT JOIN products p ON p.id = oi.product_id
            WHERE o.user_id = ?
            ORDER BY oi.id ASC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function byStore(int $storeId): array
    {
        $stmt = $this->db->prepare('SELECT DISTINCT o.*, u.name customer_name FROM orders o JOIN order_items oi ON oi.order_id = o.id JOIN users u ON u.id = o.user_id WHERE oi.store_id = ? ORDER BY o.created_at DESC');
        $stmt->execute([$storeId]);
        return $stmt->fetchAll();
    }

    public function itemsByStore(int $storeId): array
    {
        $stmt = $this->db->prepare('SELECT oi.*, o.order_code, o.order_status, o.payment_status, o.created_at FROM order_items oi JOIN orders o ON o.id = oi.order_id WHERE oi.store_id = ? ORDER BY o.created_at DESC');
        $stmt->execute([$storeId]);
        return $stmt->fetchAll();
    }

    public function sellerSummary(int $storeId): array
    {
        $stmt = $this->db->prepare('
            SELECT
                COALESCE(SUM(CASE WHEN DATE(o.created_at) = CURDATE() THEN oi.subtotal ELSE 0 END), 0) omzet_hari_ini,
                COALESCE(SUM(CASE WHEN YEAR(o.created_at) = YEAR(CURDATE()) AND MONTH(o.created_at) = MONTH(CURDATE()) THEN oi.subtotal ELSE 0 END), 0) omzet_bulan_ini,
                COUNT(DISTINCT CASE WHEN o.order_status IN ("processing", "shipped") THEN o.id END) pesanan_aktif,
                COUNT(DISTINCT CASE WHEN o.order_status = "processing" THEN o.id END) pesanan_baru,
                COUNT(DISTINCT CASE WHEN o.order_status = "completed" THEN o.id END) pesanan_selesai,
                COUNT(DISTINCT CASE WHEN o.order_status = "cancelled" THEN o.id END) pesanan_batal,
                COALESCE(SUM(oi.subtotal), 0) total_pendapatan,
                COALESCE(SUM(o.marketplace_fee), 0) total_fee_marketplace
            FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            WHERE oi.store_id = ?
        ');
        $stmt->execute([$storeId]);
        return $stmt->fetch() ?: [];
    }

    public function bestSellersByStore(int $storeId, int $limit = 5): array
    {
        $stmt = $this->db->prepare('
            SELECT oi.product_id, oi.product_name, SUM(oi.qty) qty_sold, SUM(oi.subtotal) revenue
            FROM order_items oi
            WHERE oi.store_id = ?
            GROUP BY oi.product_id, oi.product_name
            ORDER BY qty_sold DESC, revenue DESC
            LIMIT ?
        ');
        $stmt->bindValue(1, $storeId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function all(): array
    {
        return $this->db->query('
            SELECT
                o.*,
                u.name customer_name,
                COALESCE(store_summary.store_names, "-") store_names,
                COALESCE(store_summary.item_count, 0) item_count
            FROM orders o
            JOIN users u ON u.id = o.user_id
            LEFT JOIN (
                SELECT
                    oi.order_id,
                    GROUP_CONCAT(DISTINCT s.name ORDER BY s.name SEPARATOR ", ") store_names,
                    SUM(oi.qty) item_count
                FROM order_items oi
                JOIN stores s ON s.id = oi.store_id
                GROUP BY oi.order_id
            ) store_summary ON store_summary.order_id = o.id
            ORDER BY o.created_at DESC
        ')->fetchAll();
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM orders')->fetchColumn();
    }

    public function revenue(): float
    {
        return (float) $this->db->query('SELECT COALESCE(SUM(total), 0) FROM orders WHERE payment_status = "paid"')->fetchColumn();
    }

    public function storeRevenueSummary(): array
    {
        return $this->db->query('
            SELECT
                oi.store_id,
                COALESCE(SUM(oi.subtotal), 0) revenue,
                COUNT(DISTINCT oi.order_id) orders
            FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            WHERE o.payment_status = "paid"
            GROUP BY oi.store_id
        ')->fetchAll();
    }

    public function updateStatus(int $orderId, string $status, int $storeId): bool
    {
        $allowed = ['processing', 'shipped', 'completed', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $this->db->prepare('UPDATE orders o SET o.order_status = ? WHERE o.id = ? AND EXISTS (SELECT 1 FROM order_items oi WHERE oi.order_id = o.id AND oi.store_id = ?)');
        return $stmt->execute([$status, $orderId, $storeId]);
    }
}
