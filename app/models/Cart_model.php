<?php

class Cart_model
{
    public function items(): array
    {
        $cart = $_SESSION['cart'] ?? [];
        if (!$cart) {
            return [];
        }

        $productModel = new Product_model();
        $items = [];
        foreach ($cart as $productId => $qty) {
            $product = $productModel->find((int) $productId);
            if ($product && $product['stock'] > 0) {
                $qty = min((int) $qty, (int) $product['stock']);
                $items[] = [
                    'product' => $product,
                    'qty' => $qty,
                    'subtotal' => $qty * (float) $product['price'],
                ];
            }
        }

        return $items;
    }

    public function add(int $productId, int $qty): void
    {
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + max(1, $qty);
    }

    public function remove(int $productId): void
    {
        unset($_SESSION['cart'][$productId]);
    }

    public function clear(): void
    {
        unset($_SESSION['cart']);
    }

    public function summary(): array
    {
        $items = $this->items();
        $subtotal = array_sum(array_column($items, 'subtotal'));
        $marketplaceFee = round($subtotal * 0.02);
        $gatewayFee = round($subtotal * 0.005);
        $bankFee = round($subtotal * 0.01);
        $tax = round($subtotal * 0.02);
        $shipping = $subtotal > 0 ? max(5000, round($subtotal * 0.05)) : 0;

        return compact('items', 'subtotal', 'marketplaceFee', 'gatewayFee', 'bankFee', 'tax', 'shipping') + [
            'total' => $subtotal + $marketplaceFee + $gatewayFee + $bankFee + $tax + $shipping,
        ];
    }
}
