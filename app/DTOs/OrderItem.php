<?php

namespace App\DTOs;

class OrderItem
{
    public $id;
    public $order_id;

    public $product_id;
    public $sku_id;

    public $product_name;
    public $sku_code;

    public $price;
    public $quantity;

    public $image_url;

    public function __construct(array $data = [])
    {
        $this->id            = $data['id'] ?? null;
        $this->order_id      = $data['order_id'] ?? null;

        $this->product_id    = $data['product_id'] ?? null;
        $this->sku_id        = $data['sku_id'] ?? null;

        $this->product_name  = $data['product_name'] ?? null;
        $this->sku_code      = $data['sku_code'] ?? null;

        $this->price         = $data['price'] ?? 0;
        $this->quantity      = $data['quantity'] ?? 1;

        $this->image_url     = $data['image_url'] ?? null;
    }

    public function getTotal(): float
    {
        return $this->price * $this->quantity;
    }

    public function increaseQuantity(int $qty = 1)
    {
        $this->quantity += $qty;
    }

    public function decreaseQuantity(int $qty = 1)
    {
        $this->quantity -= $qty;
        if ($this->quantity < 1) {
            $this->quantity = 1;
        }
    }

    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'order_id'     => $this->order_id,
            'product_id'   => $this->product_id,
            'sku_id'       => $this->sku_id,
            'product_name' => $this->product_name,
            'sku_code'     => $this->sku_code,
            'price'        => $this->price,
            'quantity'     => $this->quantity,
            'image_url'    => $this->image_url,
        ];
    }
}